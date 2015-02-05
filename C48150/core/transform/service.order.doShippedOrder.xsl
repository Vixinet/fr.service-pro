<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0"
								xmlns="http://www.cdiscount.com"
                xmlns:x="http://www.cdiscount.com"
								xmlns:a="http://schemas.datacontract.org/2004/07/Cdiscount.Framework.Core.Communication.Messages"
								xmlns:i="http://www.w3.org/2001/XMLSchema-instance"
								xmlns:s="http://schemas.xmlsoap.org/soap/envelope/"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                exclude-result-prefixes="">
  
  <xsl:output method="xml"
              indent="yes" />
  
  <xsl:template match="/">
		<s:Envelope>
			<s:Body>
				<ValidateOrderList>
					<headerMessage>
						<a:Context>
							<a:CatalogID>1</a:CatalogID>
							<a:CustomerPoolID>1</a:CustomerPoolID>
							<a:SiteID>100</a:SiteID>
						</a:Context>
						<a:Localization>
							<a:Country>Fr</a:Country>
							<a:Currency>Eur</a:Currency>
							<a:DecimalPosition>2</a:DecimalPosition>
							<a:Language>Fr</a:Language>
						</a:Localization>
						<a:Security>
							<a:DomainRightsList i:nil="true" />
							<a:IssuerID i:nil="true" />
							<a:SessionID i:nil="true" />
							<a:SubjectLocality i:nil="true" />
							<a:TokenId><xsl:value-of select="$tokenId" /></a:TokenId>
							<a:UserName i:nil="true" />
						</a:Security>
						<a:Version>1.0</a:Version>
					</headerMessage>
					<validateOrderListMessage>
						<OrderList>
							<xsl:apply-templates select="//x:Order[x:OrderNumber=$orderid]" />
						</OrderList>
					</validateOrderListMessage>
				</ValidateOrderList>
			</s:Body>
		</s:Envelope>
  </xsl:template>
  

  <xsl:template match="x:Order">
		<ValidateOrder>
			<OrderLineList>
				<xsl:apply-templates select="x:OrderLineList/x:OrderLine[x:Sku != 'INTERETBCA']" />
			</OrderLineList>
			<OrderNumber><xsl:value-of select="x:OrderNumber" /></OrderNumber>
			<OrderState>Shipped</OrderState>
			<CarrierName>La Poste</CarrierName>
			<TrackingNumber><xsl:value-of select="$tracknumber"/></TrackingNumber>
			<TrackingUrl><xsl:value-of select="$trackurl"/></TrackingUrl>
		</ValidateOrder>
  </xsl:template>
  
	<xsl:template match="x:OrderLine">
		<ValidateOrderLine>
			<AcceptationState>ShippedBySeller</AcceptationState>
			<ProductCondition><xsl:value-of select="x:ProductCondition" /></ProductCondition>
			<SellerProductId><xsl:value-of select="x:SellerProductId" /></SellerProductId>
		</ValidateOrderLine>
	</xsl:template>
</xsl:stylesheet>