# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('blog', '0009_auto_20150123_1851'),
    ]

    operations = [
        migrations.RenameField(
            model_name='post',
            old_name='index',
            new_name='main',
        ),
    ]
