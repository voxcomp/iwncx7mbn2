<table>
    <thead>
    <tr>
	    <th>Team Name</th>
        <th>Captain First Name</th>
        <th>Captian Last Name</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Zip</th>
        <th>Phone</th>
        <th>Email</th>
        <th># of Members</th>
        <th>Goal</th>
        <th>Amount Raised</th>
    </tr>
    </thead>
    <tbody>
    @foreach($teams as $team)
        <tr>
            <td>{{ $team->name }}</td>
            <td>{{ $team->captain->fname }}</td>
            <td>{{ $team->captain->lname }}</td>
            <td>{{ $team->captain->address }}</td>
            <td>{{ $team->captain->city }}</td>
            <td>{{ $team->captain->state }}</td>
            <td>{{ $team->captain->zip }}</td>
            <td>{{ $team->captain->phone }}</td>
            <td>{{ $team->captain->email }}</td>
            <td>{{ $team->members->count() }}</td>
            <td>{{ $team->goal }}.00</td>
            <td>{{ $team->eventDonations($team->event) }}.00</td>
        </tr>
    @endforeach
    </tbody>
</table>