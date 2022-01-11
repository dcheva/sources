# import re
# from jinja2 import evalcontextfilter, Markup, escape
from projects.user.forms import *
from projects.blog.models import *

"""
local variables and lists in global array
"""


def get_local_vars(request):
    local_vars = {}
    if "styleSheet" in request.COOKIES:
        local_vars['style_sheet'] = request.COOKIES["styleSheet"]
    local_vars["user_registration_form"] = UserRegistrationForm()
    local_vars["cat_list"] = Tag.objects.all()
    local_vars["com_list"] = Comment.objects.all().order_by('-created')[:5]
    return local_vars
