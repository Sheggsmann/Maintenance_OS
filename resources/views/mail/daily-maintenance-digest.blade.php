<!DOCTYPE html>
<html>
<head>
    <title>Daily Maintenance Digest</title>
</head>
<body>

<h2>Good Morning {{ $manager->name }},</h2>

<p>Here is today's MaintenanceOS summary.</p>

<table cellpadding="8" cellspacing="0" border="1">

    <tr>
        <td>Pending Maintenance Requests</td>
        <td>{{ $digest['pendingRequests'] }}</td>
    </tr>

    <tr>
        <td>Pending Work Orders</td>
        <td>{{ $digest['pendingWorkOrders'] }}</td>
    </tr>

    <tr>
        <td>Dispatched Work Orders</td>
        <td>{{ $digest['dispatchedWorkOrders'] }}</td>
    </tr>

    <tr>
        <td>Accepted Work Orders</td>
        <td>{{ $digest['acceptedWorkOrders'] }}</td>
    </tr>

    <tr>
        <td>In Progress Work Orders</td>
        <td>{{ $digest['inProgressWorkOrders'] }}</td>
    </tr>

    <tr>
        <td>Completed Today</td>
        <td>{{ $digest['completedToday'] }}</td>
    </tr>

    <tr>
        <td>Cancelled Today</td>
        <td>{{ $digest['cancelledToday'] }}</td>
    </tr>

</table>

<p>
Please review any outstanding work orders requiring attention.
</p>

</body>
</html>