# Spotify API Sample

By Weng Fei Fung.

This is a sample code for Spotify because I did not find any sample codes in their documentation. For this sample to work, you must have a Spotify account and had saved playlists because the point of this sample code is to let you login through Spotify, then my app would pull that information of playlist names from your Spotify.


## How OAuth 2.0 works in layman terms
The sample code follows the OAuth 2.0 flow where the user is asked to login to their Spotify account, and that authorizes the app to access the information that the user allowed. The user is signing a consent to have your app access certain information from their Spotify account. The information that's allowed are called scopes. One such scope is reading the user's playlists which is what this sample code does. Once the user authorizes your app to their Spotify account information, the Spotify login page redirects you back to the referral webpage that connected to the login page in the first place with one difference: The URL has an authorization code in the URL parameters portion. You would use this authorization code to get a web token at a different Spotify API endpoint. With that web token, all future requests such as getting a list of playlists, requires you to put that web token in the authorization header. You may ask why not just give us the web token instead of having to get an authorization code then exchange it for a web token. Think of separation of concerns: The developers who came up with this OAuth 2.0 methodology would want authorization codes to keep track of who granted what types of app information, and they would want web tokens to keep track of much information is requested and in how much timeframe and the specific details of the information requested, and they don't want the two types of tracking muddled in a single database table. Also worth noting is that exchanging authorization code for web token is at a different API endpoint because the thinking behind designing endpoints is that they are named for the resource they provide.


## Max playlists

This sample code shows only 20 Spotify playlists maximum because Spotify placed a 20 playlists maximum per page. The code does not pool together multiple 20 playlists into one list, but if you want to do so, then notice that the Spotify playlists API returns an object with the keys: items, href, and total. The href is the API endpoint that has URL parameters offset and limit. Offset is basically the page number of results. The limit is the number of results per page. Total is the number of playlists. The items themselves are the playlists.

You can divide the total by 20, then round it down, then plus or not plus 1 depending on modulus 20 for the number of pages. Then for each page, you can request to the API endpoint with a different offset value.

You can look more into the object that the Spotify playlists endpoint returns by var dumping $res_json or $res_obj.

### Personalize the code

If you want to use my sample code as a basis for your own Spotify app or to incorporate Spotify into your app, you may want to edit the creds.php with your client id, client secret, and redirect URI. These can be setup at your own free personal Spotify developer account at https://developer.spotify.com/dashboard/applications. You can sign in with your normal Spotify account.

