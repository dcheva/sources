# import json
# from django.shortcuts import get_object_or_404
# from django.shortcuts import redirect
# from django.http import HttpResponse
# from django.template import RequestContext, loader
# from django.core.urlresolvers import reverse
# from django.core.paginator import Paginator, EmptyPage, PageNotAnInteger
# from django.contrib import messages
from django.shortcuts import render
from projects.blog.models import *
from helpers import functions


def index(request):
    """
    Index page
    :param request:
    :return render():
    """
    template = 'main/index.jinja2'
    local_vars = functions.get_local_vars(request)
    post_list = Post.objects.filter(main__gte=1).order_by('main')
    return render(request, template, locals())
