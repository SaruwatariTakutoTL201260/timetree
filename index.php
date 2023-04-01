<?php

$url = 'https://timetreeapis.com/calendars/mqDgcs8noUKy/upcoming_events';

$options = array(
  'http' => array(
    'header'  => "Authorization: Bearer 2e4CvM5Q2hGPPAVM-2fevuNU5cmVrGnyA9F4qq2rFBQnrz1T\r\n",
    'method'  => 'GET',
  ),
);

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

$data = json_decode($result, true);

$events = array();
foreach ($data['data'] as $event) {
    $start_time = date("Y/m/d H:i", strtotime($event['attributes']['start_at']));
    $end_time = date("Y/m/d H:i", strtotime($event['attributes']['end_at']));
    $description = isset($event['attributes']['description']) ? $event['attributes']['description'] : '';
    $events[] = array(
        'タイトル' => $event['attributes']['title'],
        '開始時間' => $start_time,
        '終了時間' => $end_time,
        '説明' => $description,
    );
}

$now = date("Y-m-dH:i:s");

$fp = fopen($now . '.csv', 'w');
fputcsv($fp, array('Title', 'Start Time', 'End Time', 'Description'));
foreach ($events as $event) {
    fputcsv($fp, $event);
}
fclose($fp);

?>

<html>
<head>
  <meta charset="UTF-8">
  <title>Upcoming Events</title>
</head>
<body>
  <h1>Upcoming Events</h1>
  <table>
    <thead>
      <tr>
        <th>タイトル</th>
        <th>開始時間</th>
        <th>終了時間</th>
        <th>説明</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($events as $event): ?>
        <tr>
          <td><?php echo $event['タイトル']; ?></td>
          <td><?php echo $event['開始時間']; ?></td>
          <td><?php echo $event['終了時間']; ?></td>
          <td><?php echo $event['説明']; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
