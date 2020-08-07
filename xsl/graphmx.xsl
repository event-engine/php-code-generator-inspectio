<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
        version="1.0"
        xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
        xmlns="http://graphml.graphdrawing.org/xmlns">

    <xsl:output method="xml" encoding="utf-8" indent="yes"/>

    <xsl:variable
            name="xpathStickies"
            select="/mxGraphModel/root/*[@type='command' or @type='event' or @type='aggregate']"/>

    <!-- attribute set definition -->

    <xsl:attribute-set name="nodeAttr">
        <xsl:attribute name="id">
            <xsl:value-of select="@id"/>
        </xsl:attribute>
    </xsl:attribute-set>

    <xsl:attribute-set name="edgeAttr">
        <xsl:attribute name="source">
            <xsl:value-of select="@source"/>
        </xsl:attribute>

        <xsl:attribute name="target">
            <xsl:value-of select="@target"/>
        </xsl:attribute>
    </xsl:attribute-set>

    <!-- template rule -->

    <xsl:template match="/">
        <graphml xmlns="http://graphml.graphdrawing.org/xmlns"
                 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                 xsi:schemaLocation="http://graphml.graphdrawing.org/xmlns http://graphml.graphdrawing.org/xmlns/1.1/graphml.xsd">

            <!-- node key definitions for data XML elements -->
            <xsl:element name="key">
                <xsl:attribute name="id">type</xsl:attribute>
                <xsl:attribute name="for">node</xsl:attribute>
                <xsl:attribute name="attr.name">type</xsl:attribute>
                <xsl:attribute name="attr.type">string</xsl:attribute>
            </xsl:element>

            <xsl:element name="key">
                <xsl:attribute name="id">label</xsl:attribute>
                <xsl:attribute name="for">node</xsl:attribute>
                <xsl:attribute name="attr.name">label</xsl:attribute>
                <xsl:attribute name="attr.type">string</xsl:attribute>
            </xsl:element>

            <xsl:call-template name="tplGraph">
                <xsl:with-param name="stickies" select="$xpathStickies"/>
            </xsl:call-template>
        </graphml>
    </xsl:template>

    <!-- template tplGraph -->

    <xsl:template name="tplGraph">
        <xsl:param name="stickies"/>
        <graph id="iio" edgedefault="directed">
            <!-- select stickies and render node XML elements -->
            <xsl:for-each select="$stickies">
                <xsl:call-template name="tplNode"/>
            </xsl:for-each>

            <xsl:for-each select="/mxGraphModel/root/*[@edge=1 and @source and @target]">
                <!-- check if edge is between selected stickies and render edge XML elements -->
                <xsl:if test="$xpathStickies/@id = ./@source and $xpathStickies/@id = ./@target">
                    <xsl:call-template name="tplEdge"/>
                </xsl:if>
            </xsl:for-each>
        </graph>
    </xsl:template>

    <!-- template tplNode -->

    <xsl:template name="tplNode">
        <xsl:element name="node" use-attribute-sets="nodeAttr">
            <xsl:element name="data">
                <xsl:attribute name="key">type</xsl:attribute>
                <xsl:value-of select="./@type"/>
            </xsl:element>

            <xsl:element name="data">
                <xsl:attribute name="key">label</xsl:attribute>
                <xsl:text disable-output-escaping="yes">&lt;![CDATA[</xsl:text>
                <xsl:value-of select="./@label" disable-output-escaping="yes"/>
                <xsl:text disable-output-escaping="yes">]]&gt;</xsl:text>
            </xsl:element>
        </xsl:element>
    </xsl:template>

    <!-- template tplEdge -->

    <xsl:template name="tplEdge">
        <xsl:element name="edge" use-attribute-sets="edgeAttr"/>
    </xsl:template>

</xsl:stylesheet>
