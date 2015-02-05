<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  
  <xsl:output method="xml" />
  
  
  <xsl:include href="page.content-loader.xsl" />
  
  <xsl:template name="content">
    <xsl:choose>
      <xsl:when test="data/header/output/session/user">
        <div class="navbar navbar-static-top">
          <div class="navbar-inner">
            <a class="brand" href="#">Marketplace Manager</a>
            <ul class="menu nav nav-tabs navbar navbar-fixed-top">
              <li class="divider-vertical"></li>
              <li><a class="xhr-link" href="#" page="product.entity">Produits</a></li>
              <li><a class="xhr-link" href="#" page="offers.manage">Nouvelles offres</a></li>
              <li><a class="xhr-link" href="#" page="pricing.watch">Vue d'ensemble</a></li>
              <li><a class="xhr-link" href="#" page="pricing.change">Pricing</a></li>
              <li class="divider-vertical"></li>
			  <li><a href="tuto.html">Edit en group</a></li>
			  <li class="divider-vertical"></li>
              <li><a class="xhr-link" href="#" page="admin.logout">Exit</a></li>
            </ul>
          </div>
        </div>
        <br/>
        <div class="container">
          <table class="table table-striped table-bordered table-condensed messages">
            <thead>
              <tr>
                <th class="span3">CODE</th>
                <th>MESSAGE</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="2"><p class="text-center">Pas de messages</p></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="container body"> </div>
      </xsl:when>
      <xsl:otherwise>
        <style type="text/css">
          body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
          }
    
          .form-signin {
            max-width: 300px;
            padding: 19px 29px 29px;
            margin: 0 auto 20px;
            background-color: #fff;
            border: 1px solid #e5e5e5;
            -webkit-border-radius: 5px;
               -moz-border-radius: 5px;
                    border-radius: 5px;
            -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
               -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                    box-shadow: 0 1px 2px rgba(0,0,0,.05);
          }
          .form-signin .form-signin-heading,
          .form-signin .checkbox {
            margin-bottom: 10px;
          }
          .form-signin input[type="text"],
          .form-signin input[type="password"] {
            font-size: 16px;
            height: auto;
            margin-bottom: 15px;
            padding: 7px 9px;
          }
        </style>
        <div class="container login">
          <form class="form-signin" onsubmit="return false;">
            <h2 class="form-signin-heading">Please sign in</h2>
            <input name="email" type="text" class="input-block-level" placeholder="Email address" />
            <input name="password" type="password" class="input-block-level" placeholder="Password" />
            <button class="btn btn-large btn-primary btn-sign-in">Sign in</button>
          </form>
        </div>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
  
</xsl:stylesheet>