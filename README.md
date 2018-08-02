# Education.govt.nz Standards - Footer.

This repository contains all the source code for the standard Ministry of 
Education footer to avoid the need to rebuild the same markup and functionality
between all the sites.

![demo](client/img/demo.png)

## Usage

In your template use the include as follows

```
<% include Education\Standards\Footer %>
```

Your SCSS will also need to include the styles

```
@import "../../vendor/education/standard-footer/client/scss/footer.scss"
```

Then you'll be good to go.

## Configuration

The footer has several parts to be created and managed in the CMS.

 - Upper Social Links (top left)
 - Upper Links (featured buttons top right)
 - Lower Links


