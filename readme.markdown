# EtuFB -- Etu's FaceBook Library

A small simple lib to authenticate users and gain a acsess token from facebooks oauth/graph API.

## State of the lib

Started and not tested in big apps, but it's small and really simple. So I think it should do the job.

## Features
* Gain code for user, and ask for extended permissions
* Gain access token without reloading the page
* Do really simple API calls to graph API, will work on this
* If the code is to old(2hrs), automatic gaining reauthing(only if user refresh browser)

## Requirements
* PHP
* Webserver
* Facebook application

Tested with PHP 5.2 with Cherokee on Gentoo. But it should work with other versions.

