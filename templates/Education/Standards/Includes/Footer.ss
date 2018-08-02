<footer id="page-foot" role="contentinfo">
    <div class="upper">
        <div class="wrapper">
            <% cached 'footer-upper', $SiteConfigCacheKey %>
            <div class="inner cf">
                <div>
                    <% with $SiteConfig %>
                        <% if $SocialMediaLinksFooter %>
                            <h3>Connect with us</h3>

                            <p class="social-links">
                                <% loop $SocialMediaLinksFooter %>
                                    <a href="$Link" title="$Title" target="_blank" class="$Type"><span>$Title</span></a>
                                <% end_loop %>

                                <% if $HasShield %>
                                    <% include Education\Cwp\Includes\Shield %>
                                <% end_if %>
                            </p>
                        <% else %>
                            <p class="social-links">&nbsp;</p>
                        <% end_if %>
                    <% end_with %>
                </div>

                <div id="footer-news-links">
                    <% with $SiteConfig %>
                        <h3>$UpperFooterLinkTitle</h3>

                        <ul>
                            <% loop $UpperFooterLinks %>
                                <li><a href="$Link" class="link-button">$Title</a></li>
                            <% end_loop %>
                        </ul>
                    <% end_with %>
                </div>
            </div> <!-- // end inner \\ -->
            <% end_cached %>
        </div> <!-- // end wrapper \\ -->
    </div> <!-- // end upper \\ -->

    <div class="lower">
        <div class="wrapper">
            <div class="inner">
                <nav>
                    <% if $EducationLandingPages %>
                    <ul>
                        <li><a href="$BaseHref">Home</a></li>
                        <% loop $EducationLandingPages %>
                            <% if $DisplayInMegaMenu || $ShowInDesktopPrimaryNav %>
                                <li><a href="$Link" data-s="$URLSegment">$MenuTitle.XML</a></li>
                            <% end_if %>
                        <% end_loop %>
                    </ul>
                    <% end_if %>

                    <ul class="alt">
                        <% cached 'footer-lower', $SiteConfigCacheKey %>
                        <% with $SiteConfig %>
                            <% if $LowerFooterLinks %>
                                <% loop LowerFooterLinks %>
                                    <li><a href="$Link">$Title</a></li>
                                <% end_loop %>
                            <% end_if %>
                        <% end_with %>
                        <% end_cached %>
                    </ul>
                </nav>

                <div class="ministry-links">
                    <p id="moe-footer-link"><% with $SiteConfig %><a href="$FooterLogoLink.Link"><% end_with %><img src="resources/education/standard-footer/client/images/footer-moe-logo.png" alt="Ministry of Education." id="min-edu-logo"/></a></p>
                </div> <!-- // end ministry-links \\ -->
            </div> <!-- // end inner \\ -->

            <div class="copyright-line">
                <p><% if CopyrightPage %><a href="$CopyrightPage.Link"><% end_if %>Copyright © New Zealand Ministry of Education<% if CopyrightPage %></a><% end_if %>. <strong><a href="#page-head-utilities">Back to top</a></strong></p>
                <p id="nz-govt-footer-link">
                    <a href="http://newzealand.govt.nz/"><img src="resources/education/standard-footer/client/images/footer-nz-govt-logo.png" alt="newzealand.govt.nz - connecting you to New Zealand central &amp; local government services" title="newzealand.govt.nz - connecting you to New Zealand central &amp; local government services"/></a>
                </p>
            </div>
        </div> <!-- // end wrapper \\ -->
    </div> <!-- // end lower \\ -->
</footer> <!-- // end page-foot \\ -->
