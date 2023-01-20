/* global module require process */
module.exports = function(grunt) {
  var path = require('path');

  require('load-grunt-config')(grunt, {
    configPath: path.join(process.cwd(), 'grunt/config'),
    jitGrunt: {
      customTasksDir: 'grunt/tasks',
      staticMappings: {
        makepot: 'grunt-wp-i18n',
      },
    },
    data: {
      i18n: {
        author: 'WidgiLabs <dev@widgilabs.com>',
        support: 'http://wpconferencetheme.com/',
        pluginSlug: 'wpconference-sponsors',
        mainFile: 'wpconference-sponsors',
        textDomain: 'wpconference-sponsors',
        potFilename: 'wpconference-sponsors',
      },
    },
  });
};
