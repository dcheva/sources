from django.http import HttpResponseRedirect
from django.shortcuts import render
from django.core.urlresolvers import reverse
from django.contrib import messages
from django.contrib import auth
from helpers import functions
from projects.user.forms import *


def index(request):
    """
    Index page
    :param request:
    :return render():
    """
    # @todo create static user page
    template = 'user/index.jinja2'
    messages.warning(request, '<h4>Todo</h4>Create user:index (cabinet) page!')
    local_vars = functions.get_local_vars(request)
    return render(request, template, locals())


def login(request):
    """
    Login controller
    :param request:
    :return HttpResponseRedirect():
    """
    if request.POST:
        username = request.POST['username']
        password = request.POST['password']
        user = auth.authenticate(username=username, password=password)
        if user is not None and user.is_active:
            # User exists and active
            auth.login(request, user)
            messages.success(request, '<h4>Successfully logged in!</h4>Congratulations!')
        else:
            # Not active or not exists or wrong password
            if user is not None:
                messages.error(request, '<h4>Login error!</h4>User is not active.')
            else:
                messages.error(request, '<h4>Login error!</h4>User is not registered or password error.')
    # Empty POST
    return HttpResponseRedirect(reverse('user:index', args=()))


def logout(request):
    """
    Logout controller
    :param request:
    :return HttpResponseRedirect():
    """
    auth.logout(request)
    return HttpResponseRedirect(reverse('user:index', args=()))


def register(request):
    """
    Register controller
    :param request:
    :return HttpResponseRedirect():
    """
    template = 'user/register.jinja2'
    if request.method == 'POST':
        register_form = UserRegistrationForm(request.POST)
        if register_form.is_valid():
            register_form.save()
            messages.success(request, '<h4>Successfully registered!</h4>Congratulations!')
            return HttpResponseRedirect(reverse('user:index', args=()))
        else:
            messages.error(request, '<h4>Form errors found!</h4>Please read all of the instructions below')
    # Empty POST
    local_vars = functions.get_local_vars(request)
    return render(request, template, locals())