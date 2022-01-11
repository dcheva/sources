from django.conf.urls import patterns
from projects.main.views import *

urlpatterns = patterns('',
                       (r'^$', index, {}, 'index'),
)
