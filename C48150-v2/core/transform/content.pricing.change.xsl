<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  
  <xsl:output method="xml" />
  
  
  <xsl:include href="page.content-loader.xsl" />
  
  <xsl:template name="content">
		<h2>En cours <xsl:value-of select="/data/transform/pricing_pm_total"/></h2>
		<form onsubmit="return false;">
			<button class="btn chk_on">Tout cocher</button>
			<xsl:text> </xsl:text>
			<button class="btn chk_off">Tout décocher</button>
			
			<button class="btn btn-pm-set-pricing pull-right">Mettre à jour</button>
			
			<br/><br/>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Produit</th>
						<th>Compétition</th>
						<th class="span2"> </th>
						<th class="span1">Produit</th>
						<th class="span1">Expé.</th>
						<th class="span1">Total</th>
						<th> </th>
					</tr>
				</thead>
				<tbody>
					<xsl:if test="count(/data/transform/pricing_pm/pricing) = 0">
						<tr><td colspan="7"><p class="text-center" style="margin:0px">Aucun résultat</p></td></tr>
					</xsl:if>
					<xsl:apply-templates select="/data/transform/pricing_pm/pricing" />
				</tbody>
			</table>
			<button class="btn btn-pm-set-pricing pull-right">Mettre à jour</button>
			<br/><br/>
		</form>
		<style>
			input.pricing_field {
				margin:0px;
			}
		</style>
  </xsl:template>
  
	<xsl:template match="pricing">
		<tr>
			<td rowspan="3">
				<strong><xsl:value-of select="sku" /></strong><br/>
				Par pack de <xsl:value-of select="pack" /><br/>
				Prix de base <xsl:value-of select="price_base"/> EUR<br/><br/>
				<a href="http://priceminister.com{url}" target="_blank">Offre #<xsl:value-of select="advertid" /></a>
			</td>
			<td rowspan="3">
				<strong><xsl:value-of select="display" /></strong>
				<br/>
				Noté <xsl:value-of select="display_rating" />
			</td>
			<td style="text-align:right">Vous</td>
			<td><xsl:value-of select="price" /></td>
			<td><xsl:value-of select="shipping" /></td>
			<td><xsl:value-of select="total" /></td>
			<td rowspan="3" style="vertical-align:middle">
				<p class="text-center" style="margin:0px">
					<input name="field_{advertid}_c" type="checkbox">
						<xsl:choose>
							<xsl:when test="price_base &gt; pricing_total"></xsl:when>
							<xsl:otherwise><xsl:attribute name="checked">true</xsl:attribute></xsl:otherwise>
						</xsl:choose>
					</input>
				</p>
			</td>
		</tr>
		<tr>
			<td style="text-align:right">Compétition</td>
			<td><xsl:value-of select="display_price" /></td>
			<td><xsl:value-of select="display_shippin" /></td>
			<td><xsl:value-of select="display_total" /></td>
		</tr>
		<tr>
			<td style="text-align:right">Conseillé</td>
			<td><input class="span1 pricing_field" name="field_{advertid}_p" type="text" value="{pricing_price}" /></td>
			<td><input class="span1 pricing_field" name="field_{advertid}_s" type="text" value="{pricing_shipping}" /></td>
			<td>
				<span>
					<xsl:if test="price_base &gt; pricing_total">
						<xsl:attribute name="class">badge</xsl:attribute>
						<xsl:attribute name="style">background:#aa0000</xsl:attribute>
					</xsl:if>
					<xsl:value-of select="pricing_total"/>
				</span>
			</td>
		</tr>
	</xsl:template>
	
</xsl:stylesheet>