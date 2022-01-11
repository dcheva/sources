from django.conf.urls import patterns
from projects.blog.views import *

urlpatterns = patterns('',
                       (r'^$', list_view, {}, 'main'),
                       (r'^(?P<page_num>\d+)/$', list_view, {}, 'page'),
                       (r'^post/(?P<pk>\d+)/$', detail_view, {}, 'post'),
                       (r'^tags/(?P<tag_alias>.+)/$', tag_view, {}, 'tag'),
                       (r'^post/(?P<pk>\d+)/comment/$', post_comment, {}, 'comment'),
                       (r'^search/$', search, {}, 'search'),
)
