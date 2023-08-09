<?php

$urlGet = 'https://jsonplaceholder.typicode.com/comments';

$ch = curl_init($urlGet);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
curl_close($ch);

$posts = json_decode($result, true);

// общее количество постов с комментариями
$postsId = array_column($posts, 'postId');

$postsUnique = array_unique($postsId);

$postsCount = count($postsUnique);

// id поста с максимумом комментов
$maxCommentPostId = array_key_first(array_count_values($postsId));

// максимальное количество комментов
foreach ($posts as $post) {
    if ($post['postId'] === $maxCommentPostId) {
        $maxCommentCount = $post['id'];
        break;
    }
}

// данные POST-запроса
$data = json_encode([
    'postsCount' => $postsCount,
    'maxCommentPostId' => $maxCommentPostId,
    'maxCommentCount' => $maxCommentCount,
]);

// url, на который отправляет данные
$urlPost = 'https://webhook.site/33dfbbe4-b16b-4a82-acf7-33667697b21f';

$ch = curl_init($urlPost);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
curl_close($ch);

var_dump($response);
