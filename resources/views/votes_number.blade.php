<table>
    <thead>
    <tr>
        <th>Genre</th>
        <th>Title</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($genreTotal as $item)
        <tr>
            <td>{{ $item->genre }}</td>
            <td>{{ $item->primaryTitle }}</td>
            <td>{{ $item->numtotal }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
