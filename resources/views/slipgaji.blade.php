<!DOCTYPE html>
<html>
<head>
  <title>Slip Gaji Karyawan</title>
  <style>
    body {
      font-family: roboto;
      position: relative; /* Add this to position the watermark */
    }
    .container {
      width: 600px;
      margin: 0 auto;
      border: 1px solid #ccc;
      padding: 20px;
      position: relative; /* Ensure watermark is within the container */
    }
    .header {
      text-align: center;
      margin-bottom: 20px;
      border-bottom: 4px solid #DBA82E
    }
    .header img {
      max-width: 200px; 
      margin-bottom: 10px; 
    }
    .info-karyawan {
      margin-bottom: 4px;
    }
    .table {
      width: 100%;
      border-collapse: collapse;
    }
    .table th, .table td {
      border: 1px solid #ccc;
      padding: 3px;
      text-align: left;
    }
    .table thead tr{
      background: #1F9DD9;
    }
    .watermark {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%); 
      opacity: 0.3;
      font-size: 40px;
      color: rgba(0, 0, 0, 0.1); /* Light gray color */
      z-index: -1; /* Place behind other content */

    }
  </style>
</head>
<body>
  <div class="container">
    

    @if($multi)

    @foreach($records as $data)
    <div class="watermark">
      @for($i=0;$i<=3;$i++)
      {{ config('app.setting.site_name') }}<br>
      @endfor
    </div>
    

    <div class="header">
      <img src="{{url('storage/'.config('app.setting.icon'))}}" />
      <h2>{{ config('app.setting.site_name') }}</h2>
    </div>
    <div>

      <div class="info-karyawan">
        <h3>Slip Gaji Karyawan</h3>
        <p>Periode: <strong>{{ Carbon\Carbon::createFromFormat('Y-m-d', $data->period_start)->format('j F Y') }} s/d 
          {{ Carbon\Carbon::createFromFormat('Y-m-d', $data->period_end)->format('j F Y') }} </strong></p>
        <p>Nama Karyawan: <strong>{{$data->karyawan->name}}</strong></p>
        <p>Jabatan: <strong>{{$data->karyawan->position}}</strong></p>
        <p>No. HP: <strong>{{$data->karyawan->phone}}</strong></p>
      </div>
  
      <div class="content">
        <h3>Penerimaan Penghasilan</h3>
        <table class="table">
          <thead>
            <tr>
              <th>Keterangan</th>
              <th>Jumlah</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Gaji Pokok</td>
              <td>Rp. {{ number_format($data->in_gaji_pokok, 0, ',', '.') }}</td> 
            </tr>
            <tr>
              <td>Upah Lembur</td>
              <td>Rp. {{ number_format($data->in_upah_lembur, 0, ',', '.') }}</td> 
            </tr>
            <tr>
              <td>Uang Makan</td>
              <td>Rp. {{ number_format($data->in_uang_makan, 0, ',', '.') }}</td> 
            </tr>
            <tr>
              <td>Uang Transport</td>
              <td>Rp. {{ number_format($data->in_uang_transport, 0, ',', '.') }}</td> 
            </tr>
            <tr>
              <td>Lain-lain ({{$data->in_keterangan}})</td>
              <td>Rp. {{ number_format($data->in_lain, 0, ',', '.') }}</td> 
            </tr>
            <tr>
              <td style="font-weight: bold;">Total Penerimaan</td>
              <td>Rp. {{ number_format($data->in_gaji_pokok + $data->in_upah_lembur + $data->in_uang_makan + $data->in_uang_transport + $data->in_lain, 0, ',', '.') }}</td> 
            </tr>
          </tbody>
        </table>
      </div>
  
      <div class="content">
        <h3>Potongan Penghasilan</h3>
        <table class="table">
          <thead>
            <tr>
              <th>Keterangan</th>
              <th>Jumlah</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Telat</td>
              <td>Rp. {{ number_format($data->out_telat, 0, ',', '.') }}</td> 
            </tr>
            <tr>
              <td>Kerusakan Barang</td>
              <td>Rp. {{ number_format($data->out_kerusakan_barang, 0, ',', '.') }}</td> 
            </tr>
            <tr>
              <td>Potongan Transport</td>
              <td>Rp. {{ number_format($data->out_uang_transport, 0, ',', '.') }}</td> 
            </tr>
            <tr>
              <td>Kasbon</td>
              <td>Rp. {{ number_format($data->out_kasbon, 0, ',', '.') }}</td> 
            </tr>
            <tr>
              <td>Lain-lain ({{$data->out_keterangan}})</td>
              <td>Rp. {{ number_format($data->out_lain, 0, ',', '.') }}</td> 
            </tr>
            <tr>
              <td style="font-weight: bold;">Total Potongan</td>
              <td>Rp. {{ number_format($data->out_telat + $data->out_kerusakan_barang + $data->out_kasbon + $data->out_lain+$data->out_uang_transport, 0, ',', '.') }}</td> 
            </tr>
          </tbody>
        </table>
      </div>
  
      <div class="gaji-bersih">
      <h3>Gaji Bersih: Rp. {{ number_format(($data->in_gaji_pokok + $data->in_upah_lembur + $data->in_uang_makan + $data->in_uang_transport + $data->in_lain) - ($data->out_telat + $data->out_kerusakan_barang + $data->out_kasbon + $data->out_lain + $data->out_uang_transport), 0, ',', '.') }}</h3> 
      </div>
        
        <b>
        GAJI SUDAH DI TRANSFER KE *({{$data->karyawan->bank_name}}) {{$data->karyawan->account_number}} a/n {{$data->karyawan->account_name}}*

        </b>
    </div>
    @pageBreak

    @endforeach
    @else
    <div class="watermark">
      
      <img src="{{public_path('storage/'.config('app.setting.logo'))}}" style="width:300px;height:300px"/>
      
    </div>
    

    <div class="header">
      <table ><tr><td style="max-width:150px;max-height:150px">
      <img src="{{public_path('storage/'.config('app.setting.logo'))}}" style="width:100px;height:100px"/>
      </td><td style="width:100%;height:auto;">
      <center>
        <h2>{{ config('app.setting.site_name') }}</h2>
        <p>{{config('app.setting.site_description')}}</p>

      </center>
      </td>
      </tr>
      </table>
    </div>
    <div class="info-karyawan">
      <h3>Slip Gaji Karyawan</h3>
      <p>Periode: <strong>{{ Carbon\Carbon::createFromFormat('Y-m-d', $data->period_start)->format('j F Y') }} s/d 
        {{ Carbon\Carbon::createFromFormat('Y-m-d', $data->period_end)->format('j F Y') }} </strong></p>
      <p>Nama Karyawan: <strong>{{$data->karyawan->name}}</strong></p>
      <p>Jabatan: <strong>{{$data->karyawan->position}}</strong></p>
      <p>No. HP: <strong>{{$data->karyawan->phone}}</strong></p>
    </div>

    <div class="content">
      <h3>Penerimaan Penghasilan</h3>
      <table class="table">
        <thead>
          <tr>
            <th>Keterangan</th>
            <th>Jumlah</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Gaji Pokok</td>
            <td>Rp. {{ number_format($data->in_gaji_pokok, 0, ',', '.') }}</td> 
          </tr>
          <tr>
            <td>Upah Lembur</td>
            <td>Rp. {{ number_format($data->in_upah_lembur, 0, ',', '.') }}</td> 
          </tr>
          <tr>
            <td>Uang Makan</td>
            <td>Rp. {{ number_format($data->in_uang_makan, 0, ',', '.') }}</td> 
          </tr>
          <tr>
            <td>Uang Transport</td>
            <td>Rp. {{ number_format($data->in_uang_transport, 0, ',', '.') }}</td> 
          </tr>
          <tr>
            <td>Lain-lain ({{$data->in_keterangan}})</td>
            <td>Rp. {{ number_format($data->in_lain, 0, ',', '.') }}</td> 
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Penerimaan</td>
            <td>Rp. {{ number_format($data->in_gaji_pokok + $data->in_upah_lembur + $data->in_uang_makan + $data->in_uang_transport + $data->in_lain, 0, ',', '.') }}</td> 
          </tr>
        </tbody>
      </table>
    </div>

    <div class="content">
      <h3>Potongan Penghasilan</h3>
      <table class="table">
        <thead>
          <tr>
            <th>Keterangan</th>
            <th>Jumlah</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Telat</td>
            <td>Rp. {{ number_format($data->out_telat, 0, ',', '.') }}</td> 
          </tr>
          <tr>
            <td>Kerusakan Barang</td>
            <td>Rp. {{ number_format($data->out_kerusakan_barang, 0, ',', '.') }}</td> 
          </tr>
          <tr>
            <td>Kasbon</td>
            <td>Rp. {{ number_format($data->out_kasbon, 0, ',', '.') }}</td> 
          </tr>
          <tr>
            <td>Potongan Transport</td>
            <td>Rp. {{ number_format($data->out_uang_transport, 0, ',', '.') }}</td> 
          </tr>
          <tr>
            <td>Lain-lain ({{$data->out_keterangan}})</td>
            <td>Rp. {{ number_format($data->out_lain, 0, ',', '.') }}</td> 
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Potongan</td>
            <td>Rp. {{ number_format($data->out_telat + $data->out_kerusakan_barang + $data->out_kasbon + $data->out_lain, 0, ',', '.') }}</td> 
          </tr>
        </tbody>
      </table>
    </div>

    <div class="gaji-bersih">
      <h3>Gaji Bersih: Rp. {{ number_format(($data->in_gaji_pokok + $data->in_upah_lembur + $data->in_uang_makan + $data->in_uang_transport + $data->in_lain) - ($data->out_telat + $data->out_kerusakan_barang + $data->out_kasbon + $data->out_lain + $data->out_uang_transport), 0, ',', '.') }}</h3> 
       
      <b>
        GAJI SUDAH DI TRANSFER KE *({{$data->karyawan->bank_name}}) {{$data->karyawan->account_number}} a/n {{$data->karyawan->account_name}}*

        </b>
    </div>
    @endif
  </div>
</body>
</html>
