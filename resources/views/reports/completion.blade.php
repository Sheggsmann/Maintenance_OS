<!DOCTYPE html>
<html>

<head>

    <title>Completion Report</title>

</head>

<body>

<h1>Maintenance Completion Report</h1>

<hr>

<p>

Reference:

{{ $workOrder->reference }}

</p>

<p>

Property:

{{ $workOrder->maintenanceRequest->property->name }}

</p>

<p>

Unit:

{{ $workOrder->maintenanceRequest->unit->unit_number }}

</p>

<p>

Title:

{{ $workOrder->maintenanceRequest->title }}

</p>

<p>

Description:

{{ $workOrder->maintenanceRequest->description }}

</p>

<p>

Completed:

{{ now()->toDateTimeString() }}

</p>

</body>

</html>