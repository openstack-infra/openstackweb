## Overview

The OpenStack Foundation Website

WHAT IS IT?

This project includes the code that powers the openstack.org website, which is
itself powered by a PHP web application called Silverstripe, and we've made
several customizations to meet the specific needs of OpenStack. More about the
Silverstripe CMS is available here: http://silverstripe.org/

This repository is designed to help other project members develop, test, and
contribute to the openstack.org website project, or to build other websites.
Note that this project is based on ther code that powers the public
openstack.org website, not the openstack cloud software itself. To
participate in building the actual OpenStack cloud platform software, go to:
http://docs.openstack.org/infra/manual/developers.html

WHY RELEASE THE SOURCE?

The OpenStack.org website helps promote OpenStack, the open source cloud
computing platform.  We felt it only make sense to share the code that powers
our website so that other open source projects might benefit, and so that
developers in our community might help us improve the code that powers the
website dedicated to promoting their favorite open source project.

A REMINDER ON TRADEMARKS:

In light of the trademarks held by the OpenStack Foundation, it is important
that you not use the code to build a website or webpage that could be confused
with the openstack.org website, including by building a site or page with the
same look and feel of the openstack.org site or by using trademarks that are
the same as or similar to marks found on the openstack.org site. For the rules
regarding other uses of OpenStack trademarks, see the OpenStack Trademark
Policy http://www.openstack.org/brand/openstack-trademark-policy/ and the
OpenStack Brand Guide http://www.openstack.org/brand/. Please contact
logo@openstack.org with any questions.

LICENSE:

Unless otherwise noted, all code is released under the APACHE 2.0 License
http://www.apache.org/licenses/LICENSE-2.0.html

WHO DO I CONTACT WITH QUESTIONS?

For now we will continue to use Lanchpad bugs to track issues:
https://bugs.launchpad.net/openstack-org/

WHAT'S INCLUDED
Included in this repository are:

Third Party:
* The Silverstripe CMS v 3.1.x (via Composer, for easy of deployment)

WHAT'S NOT INCLUDED
* Images - You'll note many missing images throughout the site. This is due to
  one of the following: trademark restrictions (see above), copyright
  restrictions, OpenStack sponsors, file size restrictions.


REQUIREMENTS FOR DEPLOYING

To run, the server environment needs:
* Apache 1.3 or greater
* PHP 5.2.0 or greater
* MySQL 5.0 or greater

INSTALLATION

The project uses composer (https://getcomposer.org/) to manage all dependencies
to install run following commands

* curl -sS https://getcomposer.org/installer | php
* php composer.phar install
* php composer.phar dump-autoload --optimize
* chmod 777 -R  vendor/ezyang/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer

DATABASE

OpenStack will provide a db dump on a weekly basis, purged of protected data.
The dump can be found
https://mycloud.rackspace.com/cloud/920805/files#object-store%2CcloudFiles%2CDFW/www.openstack.org-cron-db-backups-purged/.
The database will create one default admin user. All other data will need to be
populated by the user.

TODO:

We need detailed installation instructions to run the site locally on LAMP or
MAMP.

SUBMITTING PATCHES:

If you would like to contribute to the development of OpenStack, including this
website you must follow the steps in this page:

   http://docs.openstack.org/infra/manual/developers.html

If you already have a good understanding of how the system works and your
OpenStack accounts are set up, you can skip to the development workflow section
of this documentation to learn how changes to OpenStack should be submitted for
review via the Gerrit tool:

   http://docs.openstack.org/infra/manual/developers.html#development-workflow

Pull requests submitted through GitHub will be ignored.

