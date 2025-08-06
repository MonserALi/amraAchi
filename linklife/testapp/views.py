from django.shortcuts import render
from django.http import HttpResponse
from django.template import loader
# Create your views here.

def testapp(request):
    template = loader.get_template('index.html')
    return HttpResponse(template.render())

def index(request):
    template = loader.get_template('index.html')
    return HttpResponse(template.render())