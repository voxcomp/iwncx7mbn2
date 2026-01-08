<table>
    <thead>
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Zip</th>
        <th>Billing Address</th>
        <th>Billing City</th>
        <th>Billing State</th>
        <th>Billing Zip</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Shirt Size</th>
        <th>Mail Shirt</th>
        <th>Number of Pets</th>
        <th>Amount Paid</th>
        <th>Registration Date</th>
        <th>Team Name</th>
        <th>Goal</th>
        <th>Amount Raised</th>
        <th>Discount Code Used</th>
    </tr>
    </thead>
    <tbody>
    @foreach($registrants as $registrant)
        <tr>
            <td>{{ $registrant->fname }}</td>
            <td>{{ $registrant->lname }}</td>
            <td>{{ $registrant->address }}</td>
            <td>{{ $registrant->city }}</td>
            <td>{{ $registrant->state }}</td>
            <td>{{ $registrant->zip }}</td>
            <td>{{ $registrant->shipaddress }}</td>
            <td>{{ $registrant->shipcity }}</td>
            <td>{{ $registrant->shipstate }}</td>
            <td>{{ $registrant->shipzip }}</td>
            <td>{{ $registrant->phone }}</td>
            <td>{{ $registrant->email }}</td>
            <td>{{ strtoupper($registrant->shirt) }}</td>
            <td>{{ ($registrant->shipshirt)?'Yes':'No' }}</td>
            <td>{{ $registrant->pets }}</td>
            <td>{{ $registrant->paid }}.00</td>
            <td>{{ date("m/d/Y",strtotime($registrant->created_at)) }}</td>
            <td>{{ $registrant->teamname }}</td>
            <td>{{ $registrant->goal }}.00</td>
            <td>{{ $registrant->eventDonations($registrant->event) }}.00</td>
            <td>{{ $registrant->discountcode }}</td>
        </tr>
    @endforeach
    </tbody>
</table>