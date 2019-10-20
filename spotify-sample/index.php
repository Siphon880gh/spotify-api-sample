<?php 
// Your Spotify app
include("creds.php");
$client_id = $client_id_from_creds_php;
$client_secret = $client_secret_from_creds_php;
$uri = $uri_from_creds_php;

$hasAuthCode = isset($_GET["code"]);
$hasAccessToken = isset($_GET["access_token"]);


if( !$hasAuthCode && !$hasAccessToken ) {

  // 1. Get Authentication Code through OAuth2.0, asking user to login to their Spotify
  $data = [
    "client_id"=>$client_id , 
    "response_type"=>"code",
    "redirect_uri"=>$uri,
    "scope"=>"playlist-read-private"
  ];
  header("Location: https://accounts.spotify.com/authorize?" . http_build_query($data));

} else if( $hasAuthCode && !$hasAccessToken ) {
  // 2. Get Access Token
  $code = $_GET["code"];
  $encoded = base64_encode("$client_id:$client_secret");
  $auth = "Authorization: Basic $encoded";

  $data = [
    "grant_type"=>"authorization_code",
    "code"=>$code,
    "redirect_uri"=>$uri,
  ];
  $getQuery = http_build_query($data);

  $ch = curl_init("https://accounts.spotify.com/api/token");
  curl_setopt($ch, CURLOPT_HTTPHEADER, array("application/x-www-form-urlencoded", $auth));
  curl_setopt($ch,CURLOPT_POST, count($data));
  curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($data)); // Post params must be URI format for cURL
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // keeps output silent
  $res_json = curl_exec($ch);
  curl_close($ch);

  $res_obj = json_decode($res_json, true); // {access_token: ..., token_type: Bearer, expires_in: 3600, refresh_token:. .., scope: playlist-read-private}
  $token = $res_obj["access_token"];
  header("Location: ?code=$code&access_token=$token");

} else if( $hasAuthCode && $hasAccessToken ) {
  // 3. Make API request:
  $token = $_GET["access_token"];
  $auth = "Authorization: Bearer $token";

  $ch = curl_init("https://api.spotify.com/v1/me/playlists");
  curl_setopt($ch, CURLOPT_HTTPHEADER, array("application/x-www-form-urlencoded", $auth));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // keeps output silent
  $res_json = curl_exec($ch);
  curl_close($ch);

  $res_obj = json_decode($res_json, true); // {access_token: ..., token_type: Bearer, expires_in: 3600, refresh_token:. .., scope: playlist-read-private}

  $playlists = $res_obj["items"];
  echo "<h4>My Spotify Playlists are (Max 20. To uncap the max, README.md):</h4>";
  echo "<ul>";
  foreach($playlists as $playlist) {
    $name = $playlist["name"];
    echo "<li>$name</li>";
  }
  echo "</ul>";

  // TODO: There's pagination. It only lists the most recent 20 playlists.
  // echo $res_json;
  
} // last if
?>