<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        
        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}"> --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	      <link rel="stylesheet" href="assets/css/app.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
        <link href="{{asset("assets/css/custom.css")}}" rel="stylesheet">
        <!-- Styles -->
        <style>
          table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
          }
          
          td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
          }
          
          tr:nth-child(even) {
            background-color: #dddddd;
          }
          </style>
    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
          <div class="container">
            <div class="row py-2">
              <div class="col-md-12">
            </div>
            </div>
            <h2>Funds export</h2>
              <table>
                <tr>
                  <th>Name</th>
                  <th>ISIN</th>
                  <th>WKN</th>
                  <th>Category name</th>
                  <th>Sub Category name</th>
                </tr>
                @foreach ($funds as $fund)
                  <tr>
                    <td>{{ $fund->name }}</td>
                    <td>{{ $fund->isin }}</td>
                    <td>{{ $fund->wkn }}</td>
                    <td>{{ $fund->category->name }}</td>
                    <td>{{ $fund->subCategory->name }}</td>
                  </tr>
                 @endforeach
              </table>
        </div>

        </div>
    </body>
</html>
