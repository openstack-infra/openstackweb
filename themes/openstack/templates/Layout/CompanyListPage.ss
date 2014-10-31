		<h1>Companies Supporting The OpenStack Foundation</h1>
		<p>The OpenStack Foundation would not exist without the support of the Platinum, Gold, and Corporate Sponsors listed below.  Learn more about <a href="/join/#sponsor">how your company can help</a>.</p>

		<!-- Platinum Members -->
		<hr/>
		<div class="span-24 last">
			<h2>Platinum Members</h2>
			<p>
			OpenStack Foundation Platinum Members provide a significant portion of the funding to achieve the Foundation's mission of protecting, empowering and promoting the OpenStack community and software. Each Platinum Member's company strategy aligns with the OpenStack mission and is responsible for committing full-time resources toward the project.  There are eight Platinum Members at any given time, each of which holds a seat on the Board of Directors. Thank you to the following Platinum Members who are committed to OpenStack's success.
			</p>
		</div>

		<div class="span-24 logos last">

			<% loop DisplayedCompanies(Platinum) %>
                <a <% if IsExternalUrl %>rel="nofollow"<% end_if %> href="$ShowLink">
					<% loop Logo %>
						<span style="background-image: url({$SetWidth(138).URL});"></span>
					<% end_loop %>
					$Name</a>
			<% end_loop %>

		</div>

		<!-- Gold Members -->
		<% if DisplayedCompanies(Gold) %>
		<hr/>
		<div class="span-24 last">
			<h2>Gold Members</h2>
			<p>
			OpenStack Foundation Gold Members provide funding and pledge strategic alignment to the OpenStack mission. There can be up to twenty-four Gold Members at any given time, subject to board approval. If your organization is highly involved with OpenStack and interested in becoming a Gold Member, read more about <a href="/join">joining the Foundation</a>. Thank you to the following Gold Members who are committed to OpenStack's success.
			</p>
		</div>

		<div class="span-24 logos last">

			<% loop DisplayedCompanies(Gold) %>
                <a <% if IsExternalUrl %>rel="nofollow"<% end_if %> href="$ShowLink">
					<% loop Logo %>
						<span style="background-image: url({$SetWidth(138).URL});"></span>
					<% end_loop %>
					$Name</a>
			<% end_loop %>

		</div>
		<% end_if %>

		<!-- Corporate & Startup Members -->
		<% if DisplayedCompanies(Combined) %>
		<hr/>
		<div class="span-24 last">
			<h2>Corporate Sponsors</h2>
			<p>
			Corporate Sponsors provide additional funding to support the Foundation's mission of protecting, empowering and promoting OpenStack. If you are interested in becoming a corporate sponsor, read more about <a href="/join">supporting the Foundation</a>. Thank you to the following corporate sponsors for supporting the OpenStack Foundation.
			</p>
		</div>

		<div class="span-24 logos last">

			<% loop DisplayedCompanies(Combined) %>
                <a <% if IsExternalUrl %>rel="nofollow"<% end_if %> href="$ShowLink">
					<% loop Logo %>
						<span style="background-image: url({$SetWidth(138).URL});"></span>
					<% end_loop %>
					$Name</a>
			<% end_loop %>

		</div>
		<% end_if %>


		<!-- Mention Members -->
		<% if DisplayedCompanies(Mention) %>
		<hr/>
		<div class="span-24 last">
			<h2>Supporting Organizations</h2>
			<p>
			The resources provided provided by the Members and Sponsors are critical to making the OpenStack Foundation successful, but there are many ways to support the OpenStack mission, whether you're contributing code, building an OpenStack product or helping build the community. Below are companies who are actively involved in making OpenStack successful. If you would like your company listed here, please complete the <a href="https://openstack.echosign.com/public/hostedForm?formid=4TBJIEXJ4M7X2Q" target="_new">logo authorization form</a> and <a href="mailto:supporterlogos@openstack.org">send your logo</a>.
			</p>
		</div>

		<div class="span-24 small-logos last">

			<% loop DisplayedCompanies(Mention) %>
            <a <% if IsExternalUrl %>rel="nofollow"<% end_if %> href="$ShowLink">
					<% loop Logo %>
						<span style="background-image: url({$SetWidth(70).URL});"></span>
					<% end_loop %>
			<% end_loop %>

		</div>
		<% end_if %>
