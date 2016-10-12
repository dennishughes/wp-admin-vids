## Synopsis

This is a WordPress plugin that creates a "[**WP-Admin Vids**](http://dennishughes.ca/wp-admin-vids/)" Menu Item and Page in wp-admin panel with tools to add Youtube videos, playlists and channels within your admin panel for quick access. Requires YouTube API Key.
It also uses composer to require the [**madcoda/php-youtube-api**](https://github.com/madcoda/php-youtube-api) package on GitHub. 

I do plan to submit this plugin to WordPress.org when it is ready for it's first release. However even though this plugin is not quite ready it is still in a functional state. Even though it does admittedly have a few bugs that I am currently working on in addition to adding more features and functionality to it as well.

## Motivation

This plugin was intended to help new Wordpress users and clients easily find help tutorials to show them how to use the basics of WordPress and the custom tools that I developed for them. I originally developed this plugin with V2 of the Youtube API (XML Version) and soon after I was recieving an error from the V2 of the API was No longer available. So I began to to rebuild the plugin with V3 of the API (JSON Version) and came across [**madcoda/php-youtube-api**](https://github.com/madcoda/php-youtube-api). A basic PHP wrapper for the Youtube Data API v3 ( Non-OAuth ). So Implemented this class and have developed out the plugin form there.

## Installation

Simply download this repo's zip file and rename it to "admin-vids.zip" then upload it to your wordpress site via the add new plugin tool. Activate it, Click on the plugin in the menu and proceed to add your Youtube API key on the settings page. Once the key is added then you are ready to start adding videos, playlists and channels to the plugin within your WP Admin.

## Youtube Data API v3
- [Youtube Data API v3 Doc](https://developers.google.com/youtube/v3/)
- [Obtain API key from Google API Console](http://code.google.com/apis/console)

## License

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to:

Free Software Foundation, Inc. 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
