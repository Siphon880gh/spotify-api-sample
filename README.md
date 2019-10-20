# Spotify API Sample

By Weng Fei Fung.

This is a sample code for Spotify because I did not find any sample codes in their documentation. This sample code asks you to login to your Spotify account, then it pulls the list of playlists onto the webpage. You can request all kinds of other information as well, but playlists is a good start. Included here is an explanation on how OAuth 2.0 works, which is the methodology that Spotify uses to OK users to give information to your app.


## How OAuth 2.0 works in layman's terms
The sample code follows the OAuth 2.0 flow where the user is asked to login to their Spotify account, and that authorizes the app to access the information that the user allowed. The user is signing a consent to have your app access certain information from their Spotify account. The information that's allowed are called scopes. One such scope is reading the user's playlists which is what this sample code does. Once the user authorizes your app to their Spotify account information, the Spotify login page redirects you back to the referral webpage that connected to the login page in the first place with one difference: The URL has an authorization code in the URL parameters portion. You would use this authorization code to get a web token at a different Spotify API endpoint. With that web token, all future requests such as getting a list of playlists, requires you to put that web token in the authorization header. You may ask why not just give us the web token instead of having to get an authorization code then exchange it for a web token. Think of separation of concerns: The developers who came up with this OAuth 2.0 methodology would want authorization codes to keep track of who granted what types of app information, and they would want web tokens to keep track of much information is requested and in how much timeframe and the specific details of the information requested, and they don't want the two types of tracking muddled in a single database table. Also worth noting is that exchanging authorization code for web token is at a different API endpoint because the thinking behind designing endpoints is that they are named for the resource they provide.

If you decide to request for more information than just playlists, then you need to add other scopes at the Spotify developers dashboard (refer below to _Personalize the code_). In that dashboard, you also need to specify the webpage that the login page redirects back to. OAuth 2.0 methodology keeps a tight lid on the webpage that refers to the login page or else the API could be abused from anywhere on the internet and that would cost the server (in that case, costs Spotify). This is simply a good methodology that has put in a lot of thoughts. Spotify uses OAuth 2.0 but unfortunately they do not have sample codes.

## Max playlists

This sample code shows only 20 Spotify playlists maximum because Spotify placed a 20 playlists maximum per page. The code does not pool together multiple 20 playlists into one list, but if you want to do so, then notice that the Spotify playlists API returns an object with the keys: items, href, and total. The href is the API endpoint that has URL parameters offset and limit. Offset is basically the page number of results. The limit is the number of results per page. Total is the number of playlists. The items themselves are the playlists.

You can divide the total by 20, then round it down, then plus or not plus 1 depending on modulus 20 for the number of pages. Then for each page, you can request to the API endpoint with a different offset value.

You can look more into the object that the Spotify playlists endpoint returns by var dumping $res_json or $res_obj.

### Personalize the code

If you want to use my sample code as a basis for your own Spotify app or to incorporate Spotify into your app, you may want to edit the creds.php with your client id, client secret, and redirect URI. These can be setup at your own free personal Spotify developer account at https://developer.spotify.com/dashboard/applications. You can sign in with your normal Spotify account to get going with the developer account. Then you want to upload the code revolving around the redirect URI that you setup at Spotify Dashboard. 


