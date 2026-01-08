<table>
    <thead>
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Zip</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Date</th>
        <th>Amount</th>
        <th>Payment Type</th>
        <th>Anonymous</th>
        <th>To Participant</th>
        <th>To Team</th>
        <th>Recurring</th>
        <th>Cause</th>
    </tr>
    </thead>
    <tbody>
    @foreach($donors as $donor)
        <tr>
            <td>{{ $donor->fname }}</td>
            <td>{{ $donor->lname }}</td>
            <td>{{ $donor->address }}</td>
            <td>{{ $donor->city }}</td>
            <td>{{ $donor->state }}</td>
            <td>{{ $donor->zip }}</td>
            <td>{{ $donor->phone }}</td>
            <td>{{ $donor->email }}</td>
            <td>{{ date("m/d/Y",strtotime($donor->created_at)) }}</td>
            <td>{{ $donor->amount }}.00</td>
            <td>{{ $donor->paytype }}</td>
            <td>{{ ($donor->anonymous)?'Yes':'No' }}</td>
            <td>{{ (!is_null($donor->registrant))?$donor->registrant->fname.' '.$donor->registrant->lname:'' }}</td>
            <td>{{ (!is_null($donor->team))?$donor->team->name:'' }}</td>
            <td>{{ (is_null($donor->cancelled_on) && $donor->recurring=='YES')?'YES':'NO' }}</td>
            <td>{{ $donor->cause }}</td>
        </tr>
    @endforeach
    </tbody>
</table>