
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
