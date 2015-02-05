<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  
  <xsl:output method="xml" />
  
  
  <xsl:include href="page.content-loader.xsl" />
  
  <xsl:template name="content">
		
		<h2>Offres</h2>
		<p>
			Total : <xsl:value-of select="/data/transform/total" /> offres
		</p>
		<form onsubmit="return false">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Produit</th>
						<th>Offre</th>
						<th>Pack</th>
						<th>ID</th>
						<th>Prix</th>
					</tr>
				</thead>
				<tbody>
					<xsl:if test="count(/data/transform/offers_pm/offer) = 0">
						<tr><td colspan="5"><p class="text-center" style="margin:0px">Aucune nouvelle offre</p></td></tr>
					</xsl:if>
					<xsl:apply-templates select="/data/transform/offers_pm/offer" />
				</tbody>
			</table>
			<button class="btn btn-offers-manage-upd pull-right">Mettre à jour</button>
		</form>
  </xsl:template>
	
	<xsl:template match="offer">
		<tr>
			<td>
				<xsl:variable name="sku" select="sku" />
				<select class="sku" name="field_{advertid}_sku">
					<option></option>
					<xsl:for-each select="/data/transform/products/product">
						<option value="{.}">
							<xsl:if test="$sku = .">
								<xsl:attribute name="selected">true</xsl:attribute>
							</xsl:if>
							<xsl:value-of select="."/>
						</option>
					</xsl:for-each>
				</select>
				<strong>Appliquer: </strong>
				<a class="sku-report all">Partout</a>
				<xsl:if test="position() &gt; 1">
					- <a class="sku-report top">avant</a>
				</xsl:if>
				<xsl:if test="position() != last()">
					- <a class="sku-report bot">après</a>
				</xsl:if>
			</td>
			<td>
				<a href="http://priceminister.com{url}" target="_blank"><xsl:value-of select="label" /></a>
			</td>
			<td>
				<xsl:variable name="pack">
					<xsl:choose>
						<xsl:when test="pack != ''"><xsl:value-of select="pack" /></xsl:when>
						<xsl:otherwise><xsl:text>1</xsl:text></xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
				<input name="field_{advertid}_pack" type="text" class="span1" value="{$pack}" />
			</td>
			<td class="span3">
				<strong>AID: </strong><xsl:value-of select="advertid" /><br/>
				<strong>Mise à jour: </strong><xsl:value-of select="date_upd" />
			</td>
			<td class="span2">
				<strong>Prix: </strong><xsl:value-of select="price" /><br/>
				<strong>Expedition: </strong><xsl:value-of select="shipping" />
			</td>
		</tr>
	</xsl:template>
	
</xsl:stylesheet>