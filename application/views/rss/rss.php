<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<rss version="2.0"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:admin="http://webns.net/mvcb/"
     xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
     xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
<title><?php echo xml_convert($feed_name); ?></title>
<link><?php echo $feed_url; ?></link>
<description><?php echo convert_to_xml_character(xml_convert($page_description)); ?></description>
<dc:language><?php echo $page_language; ?></dc:language>
<dc:creator><?php echo $creator_email; ?></dc:creator>
<dc:rights><?php echo convert_to_xml_character(xml_convert($settings->copyright)); ?></dc:rights>
<?php foreach ($products as $product): ?>
<item>
    <title><?php echo convert_to_xml_character(xml_convert($product->title)); ?></title>
    <link><?php echo generate_product_url($product); ?></link>
    <guid><?php echo generate_product_url($product); ?></guid>
    <description><![CDATA[<div class="price"><p>✔ <?php echo trans("price") . ": " . print_price($product->price,$product->currency); ?></p><p>✔ <?php echo trans("condition") . ": " . trans($product->product_condition); ?></p><p>✔ <?php echo trans("location") . ": " . get_location($product); ?></p></div><div class="description"><?php echo $product->description; ?></div>]]></description>
<?php
$image = $this->file_model->get_image_by_product($product->id);
$image_path="";
if(!empty($image)){
    if($image->storage=="aws_s3"){
        $image_path=$this->aws_base_url."uploads/images/".$image->image_default;
        $file_size=12;
    }else{
        $image_path=base_url() . "uploads/images/" . $image->image_default;
        $file_size = @filesize(FCPATH . "uploads/images/" . $image_name);
    }
}
$image_path = str_replace( 'https://', 'http://', $image_path );
if (!empty($image_path)):?>
    <enclosure url="<?php echo convert_to_xml_character($image_path); ?>" length="<?php echo (isset($file_size)) ? $file_size : '';  ?>" type="image/jpeg"/>
<?php endif; ?>
    <pubDate><?php echo date('r', strtotime($product->created_at)); ?></pubDate>
    <dc:creator><?php echo convert_to_xml_character(get_shop_name_product($product)); ?></dc:creator>
</item>
<?php endforeach; ?>
</channel>
</rss>
