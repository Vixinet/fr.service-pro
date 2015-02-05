<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0"
                xmlns:x="http://www.cdiscount.com"
                xmlns:s="http://schemas.xmlsoap.org/soap/envelope/"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                exclude-result-prefixes="x s">
  
  <xsl:output method="xml"
              indent="yes"
              cdata-section-elements="sku headline category" />
  
  <xsl:template match="/">
    
    <response>
      <xsl:apply-templates select="//x:Order[x:OrderNumber=$purchaseid]" />
    </response>
    
  </xsl:template>
	
  <xsl:template match="x:Order">
		<getbillinginformationresult>
			<request>
        <user><xsl:value-of select="$login" /></user>
        <purchaseid><xsl:value-of select="x:OrderNumber" /></purchaseid>
			</request>
			<response>
				<billinginformation>
					<items>
						<xsl:apply-templates select="x:OrderLineList/x:OrderLine" />
					</items>
				</billinginformation>
			</response>
		</getbillinginformationresult>
  </xsl:template>
	
	<xsl:template match="x:OrderLine">
		<item>
			<itemid><xsl:value-of select="x:SellerProductId" /></itemid>
			<headline><xsl:value-of select="x:Sku" /></headline>
			<sku><xsl:value-of select="x:SellerProductId" /></sku>
			<itemstatus><xsl:value-of select="x:AcceptationState" /></itemstatus>
			<itemsaleprice>
				<amount><xsl:value-of select="x:PurchasePrice" /></amount>
				<currency>EUR</currency>
			</itemsaleprice>
			<quantity><xsl:value-of select="x:Quantity" /></quantity>
			<shippingsaleprice>
				<amount>
					<xsl:choose>
						<xsl:when test="position() = 0"><xsl:value-of select="x:UnitShippingCharges" /></xsl:when>
						<xsl:otherwise><xsl:value-of select="x:UnitAdditionalShippingCharges" /></xsl:otherwise>
					</xsl:choose>
				</amount>
				<currency>EUR</currency>
			</shippingsaleprice>
		</item>
	</xsl:template>
  
</xsl:stylesheet>