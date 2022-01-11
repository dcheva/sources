# CHANGELOG #

**01.04.2015**
User registration form simplified.
Some little changes.

**29.01.2015**
Tags deployed. Latest comments also. Main Blog software process finished.
Continue with bug fixing.

**23.01.2015**
Index page (main:index) ready with templates.
Images upload and show - done.
Tags realized but not deployed.
Modal messages deployed.

**21.01.2015**
Search form realized via ajax/json. Search controller in blog:search.
Buggy, but working. (search query, result link, back to results shows original page, not ajax search result).
Without paginator(only 25 results).
Without tags (tags are not realized)

**29.12.2014**
Theme selection stored in cookies. 
Dark themes added. 
Form processing (login and register) with errors and messages, bootstrap styles in forms also.
Kolovorot favicon now on pages.

**26.12.2014**
Themes selector and user login/register menus added.

**24.12.2014**
Bootstrap layouts and templates applied to main:index, blog:list and blog:post pages.

**23.12.2014**
Bootstrap blog proto layout and some bootstrap themes added

**21.12.2014**
3rd party utilities removed (Class-based views helpers changed to list_view(request) 
and detail_view(request, pk) - it is easier and more practical as for me).
Captcha added to comment form. 3rd party paginator removed from list view (need to use original)

**21.12.2014**
Template engine changed to [Jinja2 (django-jinja)](http://niwibe.github.io/django-jinja/) 

**20.12.2014**
Starts real blog app creation, based on [this manual](http://yiiframework.ru/doc/blog/ru/start.overview) 

**20.12.2014**
Bootstrap and jQuery added as static files. I do not want to use it as pip modules: 
it is hard to update and buggy in use.