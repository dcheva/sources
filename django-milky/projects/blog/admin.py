from django.contrib import admin
from projects.blog.models import *


class PostAdmin(admin.ModelAdmin):
    search_fields = ["title"]
    display_fields = "title created".split()


class TagAdmin(admin.ModelAdmin):
    search_fields = ["title"]
    display_fields = "title count".split()


class CommentAdmin(admin.ModelAdmin):
    display_fields = "post author created".split()


admin.site.register(Post, PostAdmin)
admin.site.register(Tag, TagAdmin)
admin.site.register(Comment, CommentAdmin)