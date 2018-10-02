var FiltersEnabled = 0; // if your not going to use transitions or filters in any of the tips set this to 0
var spacer="&nbsp; &nbsp; &nbsp; ";

// email notifications to admin
notifyAdminNewMembers0Tip=["", spacer+"No email notifications to admin."];
notifyAdminNewMembers1Tip=["", spacer+"Notify admin only when a new member is waiting for approval."];
notifyAdminNewMembers2Tip=["", spacer+"Notify admin for all new sign-ups."];

// visitorSignup
visitorSignup0Tip=["", spacer+"If this option is selected, visitors will not be able to join this group unless the admin manually moves them to this group from the admin area."];
visitorSignup1Tip=["", spacer+"If this option is selected, visitors can join this group but will not be able to sign in unless the admin approves them from the admin area."];
visitorSignup2Tip=["", spacer+"If this option is selected, visitors can join this group and will be able to sign in instantly with no need for admin approval."];

// transactions table
transactions_addTip=["",spacer+"This option allows all members of the group to add records to the 'Transactions' table. A member who adds a record to the table becomes the 'owner' of that record."];

transactions_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Transactions' table."];
transactions_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Transactions' table."];
transactions_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Transactions' table."];
transactions_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Transactions' table."];

transactions_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Transactions' table."];
transactions_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Transactions' table."];
transactions_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Transactions' table."];
transactions_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Transactions' table, regardless of their owner."];

transactions_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Transactions' table."];
transactions_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Transactions' table."];
transactions_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Transactions' table."];
transactions_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Transactions' table."];

// sections table
sections_addTip=["",spacer+"This option allows all members of the group to add records to the 'Storage sections' table. A member who adds a record to the table becomes the 'owner' of that record."];

sections_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Storage sections' table."];
sections_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Storage sections' table."];
sections_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Storage sections' table."];
sections_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Storage sections' table."];

sections_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Storage sections' table."];
sections_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Storage sections' table."];
sections_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Storage sections' table."];
sections_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Storage sections' table, regardless of their owner."];

sections_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Storage sections' table."];
sections_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Storage sections' table."];
sections_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Storage sections' table."];
sections_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Storage sections' table."];

// categories table
categories_addTip=["",spacer+"This option allows all members of the group to add records to the 'Categories' table. A member who adds a record to the table becomes the 'owner' of that record."];

categories_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Categories' table."];
categories_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Categories' table."];
categories_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Categories' table."];
categories_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Categories' table."];

categories_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Categories' table."];
categories_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Categories' table."];
categories_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Categories' table."];
categories_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Categories' table, regardless of their owner."];

categories_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Categories' table."];
categories_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Categories' table."];
categories_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Categories' table."];
categories_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Categories' table."];

// suppliers table
suppliers_addTip=["",spacer+"This option allows all members of the group to add records to the 'Suppliers' table. A member who adds a record to the table becomes the 'owner' of that record."];

suppliers_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Suppliers' table."];
suppliers_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Suppliers' table."];
suppliers_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Suppliers' table."];
suppliers_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Suppliers' table."];

suppliers_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Suppliers' table."];
suppliers_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Suppliers' table."];
suppliers_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Suppliers' table."];
suppliers_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Suppliers' table, regardless of their owner."];

suppliers_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Suppliers' table."];
suppliers_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Suppliers' table."];
suppliers_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Suppliers' table."];
suppliers_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Suppliers' table."];

// items table
items_addTip=["",spacer+"This option allows all members of the group to add records to the 'Items' table. A member who adds a record to the table becomes the 'owner' of that record."];

items_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Items' table."];
items_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Items' table."];
items_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Items' table."];
items_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Items' table."];

items_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Items' table."];
items_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Items' table."];
items_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Items' table."];
items_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Items' table, regardless of their owner."];

items_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Items' table."];
items_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Items' table."];
items_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Items' table."];
items_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Items' table."];

// batches table
batches_addTip=["",spacer+"This option allows all members of the group to add records to the 'Batches' table. A member who adds a record to the table becomes the 'owner' of that record."];

batches_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the 'Batches' table."];
batches_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the 'Batches' table."];
batches_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the 'Batches' table."];
batches_view3Tip=["",spacer+"This option allows each member of the group to view all records in the 'Batches' table."];

batches_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the 'Batches' table."];
batches_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the 'Batches' table."];
batches_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the 'Batches' table."];
batches_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the 'Batches' table, regardless of their owner."];

batches_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the 'Batches' table."];
batches_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the 'Batches' table."];
batches_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the 'Batches' table."];
batches_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the 'Batches' table."];

/*
	Style syntax:
	-------------
	[TitleColor,TextColor,TitleBgColor,TextBgColor,TitleBgImag,TextBgImag,TitleTextAlign,
	TextTextAlign,TitleFontFace,TextFontFace, TipPosition, StickyStyle, TitleFontSize,
	TextFontSize, Width, Height, BorderSize, PadTextArea, CoordinateX , CoordinateY,
	TransitionNumber, TransitionDuration, TransparencyLevel ,ShadowType, ShadowColor]

*/

toolTipStyle=["white","#00008B","#000099","#E6E6FA","","images/helpBg.gif","","","","\"Trebuchet MS\", sans-serif","","","","3",400,"",1,2,10,10,51,1,0,"",""];

applyCssFilter();
