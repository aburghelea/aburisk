#!/usr/bin/env python
# 
# Version 2 - with initialisation dialogue generator
#
# Lance Allen - August 2012
#
# note that seq2 numbers are for comparison with windows dialogue of USBPOER captured with USB port monitor
# 
import usb
import sys

global seq1,seq2,handle
seq1 = 0
seq2 = 748

#
# Function USB1 - execute a call
#
def USB1 (utype=0xC0, ureq=1, uvalue=0, uindex=1028, ubytes=0):
  global seq1, seq2, handle
  seq1=seq1+1
  seq2=seq2+1
  if ubytes != 7:
    status=handle.controlMsg(int(utype), int(ureq), int(ubytes),  int(uvalue),int( uindex))
  else:
    ubuffer = 0xB0,0x4,0x0,0x0,0x2,0x7
    status=handle.controlMsg(int(utype), int(ureq), ubuffer,  int(uvalue), int(uindex))
#  print "%d:%d: type: 0x%1x, req: 0x%1x, value: %d, index: %d, bytes: %d" % (seq1, seq2, utype, ureq, uvalue, uindex, ubytes)," >>: ", status
#  if ubytes == 7:
#    print "     <sent:>", ubuffer
  return status

def USB2 (vtype, vreq=1, vvalue=1028, vindex=0, vbytes=0):
  USB1(0xc0,1,33924,0,1)
  USB1(vtype,vreq,vvalue,vindex,vbytes)
  USB1(0xc0,1,33924,0,1)
  USB1(0xc0,1,33667,0,1)
  return
# Find my device - idVendor = 0x067b idProduct = 0x2303
# exit with error if not found

busses = usb.busses()
dev_found = False
for bus in busses:
  devices = bus.devices
  for dev in devices:
#    print "idVendor/idProduct (0x%04x)/(0x%04x) " % (dev.idVendor, dev.idProduct)
    if  dev.idVendor == 0x067b and dev.idProduct == 0x2303: 
      dev_found = True
      break
if dev_found:
  print "found USB device (0x%x)/(0x%x)" % (dev.idVendor,dev.idProduct)
else:
  print "USB switch not found"
  exit(-1)

# have found USB switch enumerate interfaces etc & open it
for config in dev.configurations:
#  print " configuration: ", config 
  for intfce in config.interfaces:
#    print " interface: ", intfce
     continue
handle = dev.open()
manufacturer = handle.getString(dev.iManufacturer,100)
print manufacturer
product = handle.getString(dev.iProduct,100)
print product
#
# don't do device reset or handle will be invalid
#
# unload kernel driver, initialise configuration & claim interface
#
#
try:
  handle.detachKernelDriver(0)
  print "--kernel driver unloaded"
except:
  print "  (kernel driver not loaded)"
handle.setConfiguration(config)
print "--configuration initialised"
handle.claimInterface(0)
print "--interface claimed"

#
# initialise dialogue generator from here. Mimics the Windows USBPOWER utility excatly
# but I don't know what it is doing as the manufacturer refused to give me an interface 
# or protocol specification! Numbers 800 etc are for sync with windows dialogue
#
USB2(0x40,1,1028.0)
USB2(0x40,1,1028,1)
USB2(0x40,1,1028,32)
USB2(0x40,1,1028,33)
USB2(0x40,1,1028,64)
USB2(0x40,1,1028,65)
USB2(0x40,1,1028,96)
USB2(0x40,1,1028,97)
USB2(0x40,1,1028,128)
USB2(0x40,1,1028,129)
USB2(0x40,1,1028,160)
USB2(0x40,1,1028,161)
USB1(0x40,1,0,1)
USB1(0x40,1,1,0)
USB1(0x40,1,2,68)
#800
USB1(0,1,1,0,0)
USB1(0x21,32,0,0,7)
USB1(0xc0,1,128,0,2)
USB1(0xc0,1,129,0,2)
#804
USB1(0x40,1,1,0)
USB1(0x40,1,0,1)
USB1(0x21,34,1,0,0)
#807
USB1(0xc0,1,128,0,2)
USB1(0xc0,1,129,0,2)
USB1(0x40,1,1,0,0)
#810
USB1(0x40,1,0,1,0)
USB1(0x21,34,3,0,0)
USB1(0xc0,1,128,0,2)
USB1(0x40,1,0,1,0)
USB1(0xc0,1,128,0,2)
#815
USB1(0x40,1,0,1,0)
USB1(0x21,32,0,0,7)
USB1(0x40,1,2827,2,0)
#818
USB1(0x40,1,2313,0,0)
USB1(0x40,1,2056,0,0)
#820
USB1(0xc0,1,129,0,2)
USB1(0x40,1,1,32)
USB1(0xc0,1,36237,0,4)
#
# this finishes initialisation, giving us a 0x20 status (OFF) from a 0xc0,1,129 status request
#
print "--initialisation finished"
#
# LOOP 
#
loop=1
while loop <= 1:
#
# Get status: 0x20 is OFF, 0xA0 is on
#
  status=USB1(0xc0,1,129,0,2)
  print " "
  print "interface is ",
  if status[0] == 0x20:
    print "OFF"
  if status[0] == 0xA0:
    print "ON"
#
# get command and process accordingly
#
  print " "
  print "command: ( + - ): ",
  command = sys.stdin.readline()
  if len(command) <= 1:
    USB1(0x21,34,2,0,0)
    USB1(0x21,34,0,0,0)
    handle.releaseInterface()
    print "--interface released"
    exit(0)
  if command.find("+") >=0:
    print "--switching ON"
    USB1(0x40,1,1,160)
  if command.find("-") >=0:
    print "--switching OFF"
    USB1(0x40,1,1,32)
  continue 

