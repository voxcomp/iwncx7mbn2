<table>
    <thead>
    <tr>
        <th>Date</th>
        <th>Registrations</th>
        <th>Teams</th>
        <th>Total Raised</th>
    </tr>
    </thead>
    <tbody>
    @foreach($dates as $item)
        <tr>
            <td>{{ date("m/d/Y",strtotime($item)) }}</td>
            <td>{{ (isset($registrants[$item]))?$registrants[$item]:0 }}</td>
            <td>{{ (isset($teams[$item]))?$teams[$item]:0 }}</td>
            <td>{{ ((isset($donations[$item]))?$donations[$item]:0) + ((isset($registrations[$item]))?$registrations[$item]:0) }}.00</td>
        </tr>
    @endforeach
    </tbody>
</table>