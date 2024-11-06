<?php

namespace App\Filament\Resources;

use App\Helper;
use Filament\Forms;
use App\Models\Slip;
use Filament\Tables;
use App\Models\Karyawan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use App\Filament\Resources\SlipResource\Pages;

class SlipResource extends Resource
{
    protected static ?string $model = Slip::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('karyawan_id')
                    ->required()
                    ->relationship('karyawan','name')
                    ->native(false)
                    ->preload()
                    ->live()
                    ->searchable()->columnSpanFull()
                    ->afterStateUpdated(function($set,$state){
                        $karyawan = Karyawan::find($state);
                        $gaji_pokok = $karyawan->salary;
                        $set('in_gaji_pokok', $gaji_pokok);
                    }),
                Forms\Components\Section::make('Periode Tanggal Gajian')->schema([
                Forms\Components\DatePicker::make('period_start')
                    ->required(),
                Forms\Components\DatePicker::make('period_end')
                    ->required(),
                ])->columns(2),
                
                Forms\Components\Section::make('Detail Penghasilan')->columns(2)->schema([
                Forms\Components\TextInput::make('in_gaji_pokok')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Gaji Pokok'),
                Forms\Components\TextInput::make('in_upah_lembur')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Upah lembur')->default(0),
                Forms\Components\TextInput::make('in_uang_makan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Uang Makan')->default(0),
                Forms\Components\TextInput::make('in_uang_transport')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Uang Transport')->default(0),
                Forms\Components\TextInput::make('in_lain')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Penghasilan lainnya')
                    ->default(0),
                Forms\Components\TextInput::make('in_keterangan')
                    ->required()
                    ->label('Keterangan Penghasilan Lainnya')
                    ->default('-'),
                ]),
                Forms\Components\Section::make('Detail Potongan')->columns(2)->schema([
                Forms\Components\TextInput::make('out_telat')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Telat')
                    ->default(0),
                Forms\Components\TextInput::make('out_kerusakan_barang')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Kerusakan Barang')
                    ->default(0),
                Forms\Components\TextInput::make('out_kasbon')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Kasbon')
                    ->default(0),
                    Forms\Components\TextInput::make('out_uang_transport')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Transport')
                    ->default(0),
                Forms\Components\TextInput::make('out_lain')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Potongan lainnya')
                    ->default(0),
                Forms\Components\TextInput::make('out_keterangan')
                    ->required()
                    ->label('Keterangan potongan lainnya')
                    ->default('-'),
                    
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('period_start')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('period_end')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('in_gaji_pokok')
                    ->money('IDR',locale:'id')
                    ->sortable(), 
                Tables\Columns\TextColumn::make('gaji_bersih')
                ->label('Gaji Bersih') // Add a clear label
                ->getStateUsing(function ($record) {
                    return "Rp ".number_format(
                        ($record->in_gaji_pokok + $record->in_upah_lembur + $record->in_uang_makan + $record->in_uang_transport + $record->in_lain) - 
                        ($record->out_telat + $record->out_kerusakan_barang + $record->out_kasbon + $record->out_lain + $record->out_uang_transport), 
                        0, ',', '.'
                    );
                })
                ->sortable(),
                Tables\Columns\TextColumn::make('Bayar')->getStateUsing(function ($record) {
                    return '('.$record->karyawan->bank_name.') '.$record->karyawan->account_number.' a/n '.$record->karyawan->account_name;
                }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Print')->icon('heroicon-s-printer')->color('success')->action(fn($record) => redirect('/print-payroll?slip_id='.$record->id))
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Print Selected')->icon('heroicon-s-printer')->color('success')->action(function($records){
                        
                        $DIR = storage_path('app/public/exported_'.date('dmYHi'));
                        if(!is_dir($DIR))
                        {
                            mkdir($DIR,0777, true);
                        }
                        $files = [];
                        foreach($records as $idx=>$record)
                        {
                            $slip_id = $record->id;
                            $slip = Slip::find($slip_id);
                            $filename = 'SLIP-GAJI_'.str_replace(' ','_',$slip->karyawan->name).'x'.date('dmYHi').'.pdf';
                            $pdf = Pdf::loadView('slipgaji',['data' => $slip,'multi' => false]);
                            $pdf->save($DIR.'/'.$filename);
                            usleep(500);
                            Helper::sendWhatsapp($slip->karyawan->phone,Helper::messageTemplate($slip) , $DIR.'/'.$filename , 'exported_'.date('dmYHi'));

                            $files[$idx] = $DIR.'/'.$filename;
                        }

                        $zip = new \ZipArchive();
                        if($zip->open($DIR.'/'.basename($DIR).'.zip' , \ZipArchive::CREATE) === TRUE)
                        {
                            foreach($files as $file)
                            {
                                $zip->addFile($file, basename($file));
                            }
                            $zip->close();

                        }

                        return response()->download($DIR.'/'.basename($DIR).'.zip');

                    })
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSlips::route('/'),
            'create' => Pages\CreateSlip::route('/create'),
            'view' => Pages\ViewSlip::route('/{record}'),
            'edit' => Pages\EditSlip::route('/{record}/edit'),
        ];
    }
}
