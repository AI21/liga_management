<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja-JP">
<head>
  <title>リーガ東海 管理ページ [<?php echo $pageTitle; ?>]</title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?php
// CSSファイル読み込みタグの生成
if (count($readCssFile) > 0) : ?>
  <meta http-equiv="Content-Style-Type" content="text/css" />
<?php foreach ($readCssFile as $cssFiles) : ?>
  <link rel="stylesheet" type="text/css" media="all" href="./css/<?php echo $cssFiles; ?>.css" />
<?php endforeach;endif; ?>
<?php
// javascriptファイル読み込みタグの生成
if (count($readJsFile) > 0) : ?>
  <meta http-equiv="Content-Script-Type" content="text/javascript" />
<?php foreach ($readJsFile as $jsFiles) : ?>
  <script type="text/javascript" src="./js/<?php echo $jsFiles; ?>.js"></script>
<?php endforeach;endif; ?>

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAEUlSM4Zloz5Wo23gSiY7wxRzROev_34beb-fX-JhQM6n4h7hGhSXJjERa4H60cO3pwGX-6KQWB5Tyw&amp;ie=utf-8&amp;oe=utf-8" type="text/javascript"></script>

<script language="javascript" type="text/javascript">
//<![CDATA[

// 会場登録
function halldataChangeConfirm() {
  var myReturn = confirm("会場データを更新しますがよろしいですか？");
  if ( myReturn == true ) {
    document.fmHall.submit();
  }
}

  var map;
  var geocoder;
  function load() {
    if (GBrowserIsCompatible()) {

      // 初期表示中心点
      var latlngCenter = new GLatLng(<?php echo $latitude;?>, <?php echo $longitude;?>);
      // マーカー表示位置
      var latlngMarker = new GLatLng(<?php echo $latitude;?>, <?php echo $longitude;?>);
      //地図を作成
      map = new GMap2(document.getElementById("map"));
      map.setCenter(latlngCenter, 13);
      //マーカーを作成
      var marker = new GMarker(latlngMarker);
      //マップコントローラを表示
      map.addControl(new GMapTypeControl());
      map.addControl(new GLargeMapControl());

      map.addControl(new GSmallZoomControl());

      geocoder = new GClientGeocoder();

      //マーカーを地図上に配置
      map.addOverlay(marker);
    }
    GEvent.addListener(map, 'dblclick', function(overlay, point) {
      if (point) {
        document.getElementById("show_x").value = point.x;
        document.getElementById("show_y").value = point.y;
      }
    });

    GEvent.addListener(map, "click", clicked);
  }

  function clicked(overlay, latlng) {
    if (latlng) {
      geocoder.getLocations(latlng, function(addresses) {
        if(addresses.Status.code != 200) {
          alert("reverse geocoder failed to find an address for " + latlng.toUrlValue());
        }
        else {
          address = addresses.Placemark[0];

          //var myZip = address2.AddressDetails.Country.PostalCode.PostalCodeNumber;
          var myPlace = address.AddressDetails.Country.AdministrativeArea.AdministrativeAreaName;
          var myCity = address.AddressDetails.Country.AdministrativeArea.Locality.LocalityName;
          var myAddress = address.AddressDetails.Country.AdministrativeArea.Locality.DependentLocality.DependentLocalityName;
          var myAddress2 = address.AddressDetails.Country.AdministrativeArea.Locality.DependentLocality.Thoroughfare.ThoroughfareName;
          document.getElementById("place").value = myPlace;
          document.getElementById("city").value = myCity;
          document.getElementById("address1").value = myAddress + myAddress2;
        }
      });
    }
  }


//]]>
</script>

<style type="text/css">
/*<![CDATA[*/
<!--

  table th {
    width:100px;
    background-color:#9097A9;
  }

-->
/*]]>*/
</style>
</head>
