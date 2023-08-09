<?php

$urlGet = 'https://jsonplaceholder.typicode.com/comments';

$context = stream_context_create(array(
    'http' => array(
        'method' => 'GET',
    ),
));

$result = file_get_contents($urlGet, false, $context);

$posts = json_decode($result, true);

// общее количество постов с комментариями

for ($i = 0; $i < count($posts); $i++ ) {

    if (!isset($posts[$i+1])) {
        $countPost[] = $posts[$i]['postId'];
        break;
    } elseif ($posts[$i]['postId'] === $posts[$i+1]['postId']) {
        continue;
    } else {
        $countPost[] = $posts[$i]['postId'];
    }
}

$postsCount = count($countPost);
//print_r($postsCount);
//echo PHP_EOL;

// id поста с максимумом комментов

$maxCommentPostId = $posts[0]['postId'];

$maxCount = 0;
$curCount = 0;

for ($i = 0; $i < count($posts); $i++ ) {

    if (!isset($posts[$i+1])) {
        break;
    } elseif ($posts[$i]['postId'] === $posts[$i+1]['postId']) {
        $curCount++;
    } elseif($curCount > $maxCount) {
        $maxCommentPostId = $posts[$i]['postId'];
        $curCount = 0;
    }
}
//print_r($maxCommentPostId);
//echo PHP_EOL;

// максимальное количество комментов

foreach ($posts as $post) {
    if ($post['postId'] === $maxCommentPostId) {
        $maxCommentCount = $post['id'];
        break;
    }
}
//print_r($maxCommentCount);

$result = json_encode([
    'postsCount' => $postsCount,
    'maxCommentPostId' => $maxCommentPostId,
    'maxCommentCount' => $maxCommentCount,
]);

//print_r($result);

$urlPost = 'https://webhook.site/33dfbbe4-b16b-4a82-acf7-33667697b21f';

$params = array($result);
$result = file_get_contents($urlPost, false, stream_context_create(array(
    'http' => array(
        'method'  => 'POST',
        'content' => http_build_query($params)
    )
)));