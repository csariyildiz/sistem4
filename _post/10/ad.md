128 bit
Kısaltmalar
2000 - 3FFF Global unicast
prefix 
  /23  ilk 23 bit 
  /32 ISP
  /48 ISP altı organizasyonlar şirketler
  /64 yerel
  
Global Unicast 2000:/3 -> public ipv4
Local Unicast
Unique Local : private ipv4
Link Local : APIPA gibi. routable değil
::1 / 128 -> loopback


```
en
conf t
ipv6 unicast-routing
int g0/0
ipv6 enable
no ip address
ipv6 address
no sh

```


```
int se0/0/0
ipv6 enable
ipv6 2001:2:3:5::1
```
dd
* no ip address v4 ü kaldırır. opsiyonel

