<table>
    <tr>
        <th>Name</th>
        <th>statut</th>
        <th>date</th>
        <th>last seen</th>
    </tr>
    @foreach ($users as $us)
    <tr>
            <td>{{$us->firstname}}</td>
            <td>
                @if (Cache::has('is_online'. $us->id))
                    Online
                @else
                    Offline
                @endif
            </td>
            <td>{{ Carbon\Carbon::parse()->diffForHumans() }}</td>
            <td>{{ $us->last_seen }}</td>
    </tr>
    @endforeach
</table>