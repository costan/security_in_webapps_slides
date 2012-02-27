# encoding: utf-8

require 'rubygems'
require 'bundler'
begin
  Bundler.setup(:default, :development)
rescue Bundler::BundlerError => e
  $stderr.puts e.message
  $stderr.puts "Run `bundle install` to install missing gems"
  exit e.status_code
end
require 'rake'

APP_ROOT = File.dirname(__FILE__) unless defined?(APP_ROOT)
require 'codex/rake/codextask'
Codex.root = APP_ROOT

Codex::Rake::CodexTask.new do |c|
  # c.content_dir = 'content'
  # c.output_dir  = 'html'
  # c.metadata    = "config/metadata.yml"
end
