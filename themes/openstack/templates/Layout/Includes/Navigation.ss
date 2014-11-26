<nav class="navbar navbar-default" role="navigation">

    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="brand-wrapper">
                <a class="navbar-brand" href="/"></a>
            </div>
            <div class="search-icon show"><i class="fa fa-search"></i> Search</div>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <div class="search-container tiny">
               <% include GoogleCustomSearch %>
               <i class="fa fa-times close-search"></i>
           </div>
           <ul class="nav navbar-nav navbar-main show">
            <li>
                <% include GoogleCustomSearchMobile %>
            </li>
            <li>
                <a href="/software/" class="drop" id="dropdownMenuSoftware">Software <i class="fa fa-caret-down"></i></a>
                <ul class="dropdown-menu dropdown-hover" role="menu" aria-labelledby="dropdownMenuSoftware">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/software/">Overview</a></li>
                    <li role="presentation" class="divider"></li>

                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/software/openstack-compute/">Compute</a></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/software/openstack-storage/">Storage</a></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/software/openstack-networking/">Networking</a></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/software/openstack-dashboard/">Dashboard</a></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/software/openstack-shared-services/">Shared Services</a></li>



                    <li role="presentation" class="divider"></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/software/start/">Get Started</a></li>


                </ul>

            </li>

            <li>

                <a href="/user-stories/" class="drop" id="dropdownMenuUsers">Users <i class="fa fa-caret-down"></i></a>
                <ul class="dropdown-menu dropdown-hover" role="menu" aria-labelledby="dropdownMenuUsers">


                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/user-stories/">Overview</a></li>



                    <li role="presentation" class="divider"></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="//superuser.openstack.org/">Superuser Magazine</a></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="http://www.openstack.org/enterprise/auto/">Featured:  Top 10 Automaker</a></li>


                </ul>

            </li>

            <li>

                <a href="/community/" class="drop" id="dropdownMenuCommunity">Community <i class="fa fa-caret-down"></i></a>
                <ul class="dropdown-menu dropdown-hover" role="menu" aria-labelledby="dropdownMenuCommunity">


                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/community/">Welcome! Start Here</a></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="//ask.openstack.org/">Ask A Technical Question</a></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="//wiki.openstack.org/wiki/Main_Page">OpenStack Wiki</a></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/community/events/">Community Events</a></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/foundation/">Openstack Foundation</a></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="//wiki.openstack.org/wiki/Getting_The_Code">Source Code</a></li>



                    <li role="presentation" class="divider"></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/foundation/companies/">Supporting Companies</a></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/community/jobs/">Jobs</a></li>



                    <li role="presentation" class="divider"></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/join/">Join The Community</a></li>


                </ul>

            </li>

            <li>

                <a href="/marketplace/">Marketplace</a>

            </li>

            <li>

                <a href="/events/" class="drop" id="dropdownMenuEvents">Events <i class="fa fa-caret-down"></i></a>
                <ul class="dropdown-menu dropdown-hover" role="menu" aria-labelledby="dropdownMenuEvents">


                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/community/events/">Overview</a></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/summit/openstack-paris-summit-2014/">The OpenStack Summit</a></li>



                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/community/events/">More OpenStack Events</a></li>


                </ul>

            </li>

            <li>

                <a href="/blog/">Blog</a>

            </li>

            <li>

                <a href="http://docs.openstack.org/">Docs</a>

            </li>


            <li>
            <% if CurrentMember %>
                <a class="sign-in-btn" href="/Security/logout/">Log Out</a>
            <% else %>
                <a class="sign-in-btn" href="/Security/login/?BackURL=%2Fprofile%2F">Sign In</a>
            <% end_if %>
            </li>
        </ul>
    </div>
    <!-- /.navbar-collapse -->
</div>
<!-- /.container -->
</nav>