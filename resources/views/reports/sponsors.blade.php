<table>
    <thead>
    <tr>
        <th>Company</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Zip</th>
        <th>Phone</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Sponsor Level</th>
        <th>In-Kind Value</th>
        <th>Amount Paid</th>
        <th>Payment Type</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sponsors as $sponsor)
        <tr>
            <td>{{ $sponsor->company }}</td>
            <td>{{ $sponsor->address }}</td>
            <td>{{ $sponsor->city }}</td>
            <td>{{ $sponsor->state }}</td>
            <td>{{ $sponsor->zip }}</td>
            <td>{{ $sponsor->phone }}</td>
            <td>{{ $sponsor->fname }}</td>
            <td>{{ $sponsor->lname }}</td>
            <td>{{ $sponsor->email }}</td>
            <td>{{ $sponsor->level }}</td>
            <td>{{ ($sponsor->level=='In-Kind')?$sponsor->inkind_value:'' }}</td>
            <td>{{ $sponsor->paid }}</td>
            <td>{{ $sponsor->paytype }}</td>
            <td>{{ date("m/d/Y",strtotime($sponsor->created_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>