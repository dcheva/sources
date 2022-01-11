# from django.template import RequestContext
# from django.db.models import Q
import json
from django.shortcuts import get_object_or_404
from django.shortcuts import render
from django.shortcuts import redirect
from django.http import HttpResponse
from django.template import loader
from django.core.urlresolvers import reverse
from django.core.paginator import Paginator, EmptyPage, PageNotAnInteger
from django.contrib import messages
from projects.blog.forms import *
from helpers import functions


def list_view(request, page_num=1):
    """
    Post list view with comments count and paginator
    :param request:
    :param page_num:
    :return render():
    """
    template = 'blog/list.jinja2'
    local_vars = functions.get_local_vars(request)
    post_list = Post.objects.order_by('-created')
    paginator = Paginator(post_list, 10)
    try:
        post_list = paginator.page(page_num)
    except PageNotAnInteger:
        # If page is not an integer, deliver first page.
        post_list = paginator.page(1)
    except EmptyPage:
        # If page is out of range (e.g. 9999), deliver last page of results.
        post_list = paginator.page(paginator.num_pages)
    return render(request, template, locals())


def tag_view(request, tag_alias='', page_num=1):
    """
    Post list view for concrete tag
    :param request:
    :param tag_alias:
    :param page_num:
    :return render():
    """
    template = 'blog/list.jinja2'
    local_vars = functions.get_local_vars(request)
    post_list = Post.objects.filter(tags__alias=tag_alias).order_by('-created')
    tag_list = Tag.objects.filter(alias=tag_alias)
    paginator = Paginator(post_list, 10)
    try:
        post_list = paginator.page(page_num)
    except PageNotAnInteger:
        # If page is not an integer, deliver first page.
        post_list = paginator.page(1)
    except EmptyPage:
        # If page is out of range (e.g. 9999), deliver last page of results.
        post_list = paginator.page(paginator.num_pages)
    return render(request, template, locals())


def detail_view(request, pk):
    """
    Post detail view with comment list and comment form
    :param request:
    :param pk:
    :return render():
    """
    template = 'blog/post.jinja2'
    local_vars = functions.get_local_vars(request)
    post = get_object_or_404(Post, pk=pk)
    print post.body
    comment_list = Comment.objects.filter(post_id=pk).order_by('-created')
    form = CommentForm()
    return render(request, template, locals())


def post_comment(request, pk):
    """
    Add comment form controller with validators and errors output
    :param request:
    :param pk:
    :return:
    """
    template = 'blog/post.jinja2'
    local_vars = functions.get_local_vars(request)
    post = get_object_or_404(Post, pk=pk)
    comment = Comment(author=request.POST['author'], body=request.POST['body'], post=post)
    if request.POST:
        form = CommentForm(request.POST)
        # Validate the form: the captcha field will automatically check the input
        if form.is_valid():
            comment.save()
        else:
            # form error
            error_message = "Form error!"
            return render(request, template, locals())
    else:
        # empty POST
        error_message = "Wrong request!"
        return render(request, template, locals())
        pass
    return redirect(reverse('blog:post', args=(post.id,)))


def search(request):
    """
    Search results page
    :param request:
    :return HttpResponse(json.dumps):
    """
    response_data = {}
    if request.POST:
        text = request.POST['text']
        response_data['status'] = 'ok'
        response_data['text'] = text
        # search here
        criteria = Q(title__icontains=text)
        criteria.add(Q(body__icontains=text), Q.OR)
        post_list = Post.objects.filter(criteria)[:25]
        template = 'blog/search.jinja2'
        local_vars = functions.get_local_vars(request)
        template = loader.get_template(template)
        response_data['content'] = template.render(locals())
    else:
        # empty POST
        local_vars = functions.get_local_vars(request)
        messages.error(request, '<h4>Bad request!</h4>Fill search form!')
        return redirect(reverse('blog:main'))
    return HttpResponse(json.dumps(response_data), content_type="application/json")