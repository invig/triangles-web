Triangles
=========

Triangles is an open source set of podcast clients and server API.

triangles-web is a web podcast client written in PHP that contains an API for app integrations.

Want to try it out? Visit [triangles.lab82.com](https://triangles.lab82.com) -- (In active development, it could break at any time!)

![Podcasts](http://lab82.com/podcasts.png)

![Unplayed](http://lab82.com/unplayed.png)

![Playing](http://lab82.com/playing.png)

###Current features:

- User accounts.
- Account number limit (to prevent overloading a new server).
- Supports episode playback on the web.
- Saves playback position and finished state.
- Combined un-played episode listing.
- Lists all episodes of a podcast, separates played and un-played.
- Supports adding from feed url and adding from podcasts OPML file.
- Feed parser currently runs every 30 minutes and attempts to parse all feeds.

###Coming soon:

- Better responsiveness for the main menu.
- Unsubscribe from podcast.
- Execute feed parser from command line.
- Parse feeds of new podcasts immediately.
- Sync API
- Mobile clients that sync data, allow playback, download episodes, and work with bluetooth car audio.
- Sign in with Twitter / other social graph providers
- Caching and resizing of images to speed up loads.
- Mark all but most recent episodes of an OMPL import as played.

###Future plans:

- Push notifications.
- Serious feed parsing (feeds are a nightmare, sophisticated parsing is probably going to be required to crawl through them and attempt to discover the info we need).
- Process new episodes in an Inbox.
- Show notes.
- Social graph.
- Sharing episodes and sharing specific sections of episodes.
- Social listening (shared listening experiences, + comments?).
- Social discovery (recommended, most played, people like you enjoy...).
- Playback analytics.
- A watch interface.

#Contributions welcome!