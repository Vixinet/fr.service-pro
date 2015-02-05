<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0"
                xmlns:x="http://www.cdiscount.com"
                xmlns:s="http://schemas.xmlsoap.org/soap/envelope/"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                exclude-result-prefixes="x s">
  
  <xsl:output method="xml"
              indent="yes"
              cdata-section-elements="lastname firstname address1 address2 purchasebuyerlogin city country" />
  
  <xsl:template match="/">
    
    <response>
      <xsl:apply-templates select="//x:Order[x:OrderNumber=$purchaseid]" />
    </response>
    
  </xsl:template>
  

  <xsl:template match="x:Order">
    <getshippinginformationresult>
      <request>
        <user><xsl:value-of select="$login" /></user>
        <purchaseid><xsl:value-of select="x:OrderNumber" /></purchaseid>
      </request>
      <response>
        <shippinginformation>
          <orderstate><xsl:value-of select="x:OrderState"/></orderstate>
          <shippingtype><xsl:value-of select="x:ShippingCode"/></shippingtype>
          <purchaseshippingcostprice>
            <amount><xsl:value-of select="x:ValidatedTotalShippingCharges"/></amount>
            <currency>EUR</currency>
          </purchaseshippingcostprice>
          <deliveryaddress>
            <civility>
              <xsl:choose>
                <xsl:when test="x:ShippingAddress/x:Civility = 'MR'">Mr.</xsl:when>
                <xsl:when test="x:ShippingAddress/x:Civility = 'MME'">Mme</xsl:when>
                <xsl:when test="x:ShippingAddress/x:Civility = 'MLLE'">Mlle</xsl:when>
                <xsl:otherwise>
                  <xsl:value-of select="x:ShippingAddress/x:Civility"/>
                </xsl:otherwise>
              </xsl:choose>
            </civility>
            <lastname>
              <xsl:value-of select="x:ShippingAddress/x:CompanyName"/>
              <xsl:text> </xsl:text>
              <xsl:value-of select="x:ShippingAddress/x:LastName"/>
            </lastname>
            <firstname><xsl:value-of select="x:ShippingAddress/x:FirstName"/></firstname>
            <address1>
              <xsl:value-of select="x:ShippingAddress/x:Address1"/>
              <xsl:text> </xsl:text>
              <xsl:value-of select="x:ShippingAddress/x:Street"/>
              <xsl:text> </xsl:text>
              <xsl:value-of select="x:ShippingAddress/x:ApartmentNumber"/>
              <xsl:text> </xsl:text>
            </address1>
            <address2>
              <xsl:value-of select="x:ShippingAddress/x:Address2"/>
              <xsl:text> </xsl:text>
              <xsl:value-of select="x:ShippingAddress/x:Building"/>
              <xsl:text> </xsl:text>
              <xsl:value-of select="x:ShippingAddress/x:PlaceName"/>
              <xsl:text> </xsl:text>
            </address2>
            <zipcode><xsl:value-of select="x:ShippingAddress/x:ZipCode"/></zipcode>
            <city><xsl:value-of select="x:ShippingAddress/x:City"/></city>
            <country>
              <xsl:choose>
                <xsl:when test="x:ShippingAddress/x:Country = 'FR'">France</xsl:when>
                <xsl:otherwise><xsl:value-of select="x:ShippingAddress/x:Country"/></xsl:otherwise>
              </xsl:choose>
            </country>
            <phonenumber1><xsl:value-of select="x:Customer/x:MobilePhone"/></phonenumber1>
            <phonenumber2><xsl:value-of select="x:Customer/x:Phone"/></phonenumber2>
          </deliveryaddress>
        </shippinginformation>
      </response>
    </getshippinginformationresult>
  </xsl:template>
  
</xsl:stylesheet>