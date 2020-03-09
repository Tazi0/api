
# My first API
Mijn eerste API die ik heb gemaakt in 4-3-2020
**Base URL**
  https://example.com/api
  
### Authorization
in the header you put `Authorization` -> your special key

## GET
**URL Params:**
|Field|Value|Required|
|--|--|--|
| table| the table you want |yes
| row| a specific row | (optional)
| limit| how many you want| (optional)

**URL:** https://example.com/api/table/row/limit <br>
**Example:** https://example.com/api/category/name/3 <br>
**Success Response:**
```
{
	"code":  200,
	"data":  [
		{"name":  "tafellamp"},
		{"name":  "buitenlamp"},
		{"name":  "Dieren lampen"}
	]
}
```

## POST
**URL Params:**
|Field|Value|Required|
|--|--|--|
| table| the table you want |yes

**Body Params:**
|Field|Value|Required|
|--|--|--|
| row| the row you want to define (joined by "-") |yes
| value| the values you want to define (joined by "-")| yes

**URL:** https://example.com/api/table <br>
**Example:** https://example.com/api/category
## DELETE
**URL Params:**
|Field|Value|Required|
|--|--|--|
| table| the table you want |yes
| id| The specific id of a item | yes

**URL:** https://example.com/api/table/id <br>
**Example:** https://example.com/api/category/12

## PUT
**URL Params:**
|Field|Value|Required|
|--|--|--|
| table | the table you want |yes
| id | the specific id | yes
| row | a specific row | yes
| value | how many you want| yes

**URL:** https://example.com/api/key/table/id/row/value <br>
**Example:** http://example.com/api/category/13/name/working

## Responces
* **Success Response:**
  * **Code:** 200 <br>
     **Content:** `{data: [{id: 12}] }`
 
* **Error Response:**
  * **Code:** 401 UNAUTHORIZED <br>
    **Content:** `{ error : "you don't have a key" }`
  * **Code:** 422 UNPROCESSABLE ENTRY <br>
    **Content:** `{ error : "please give an ..." }`
  * **Code:** 409 CONFLICT <br>
    **Content:** `{ error : "..." }`
