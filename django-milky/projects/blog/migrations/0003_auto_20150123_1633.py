# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('blog', '0002_auto_20141221_0708'),
    ]

    operations = [
        migrations.CreateModel(
            name='Tag',
            fields=[
                ('id', models.AutoField(verbose_name='ID', serialize=False, auto_created=True, primary_key=True)),
                ('title', models.CharField(max_length=60)),
                ('alias', models.CharField(max_length=60, null=True)),
                ('count', models.IntegerField(default=0)),
                ('post', models.ForeignKey(related_name='tags', to='blog.Post')),
            ],
            options={
            },
            bases=(models.Model,),
        ),
        migrations.AddField(
            model_name='post',
            name='alias',
            field=models.CharField(max_length=60, null=True),
            preserve_default=True,
        ),
        migrations.AddField(
            model_name='post',
            name='image',
            field=models.ImageField(height_field=300, width_field=900, null=True, upload_to=b''),
            preserve_default=True,
        ),
        migrations.AddField(
            model_name='post',
            name='show',
            field=models.BooleanField(default=True),
            preserve_default=True,
        ),
    ]
