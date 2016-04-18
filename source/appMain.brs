'********************************************************************
'**  Video Player Example Application - Main
'**  November 2009
'**  Copyright (c) 2009 Roku Inc. All Rights Reserved.
'********************************************************************

Sub Main()

    'initialize theme attributes like titles, logos and overhang color
    initTheme()





 'Registration
    
    'TESTING - REMOVE
    'deleteRegistrationToken()

    'Check for registration here
     m.token = loadRegistrationToken()
     
  
    ' Token is not available - need to register
    if m.token="" then
        status = doRegistration()
        
        if(status = 0)
            print "We are all good"
        end if
        
        'Load token again from registry
        
        m.token = m.regCode
        

        
     end if
        
        'TODO - Check here if Token is still valid.
        
     
    'prepare the screen for display and get ready to begin
    screen=preShowHomeScreen("", "")
    if screen=invalid then
        print "unexpected error in preShowHomeScreen"
        return
    end if

        'set to go, time to get started
        showHomeScreen(screen)
   

   
    

End Sub


'*************************************************************
'** Set the configurable theme attributes for the application
'** 
'** Configure the custom overhang and Logo attributes
'** Theme attributes affect the branding of the application
'** and are artwork, colors and offsets specific to the app
'*************************************************************

Sub initTheme()

    app = CreateObject("roAppManager")
    theme = CreateObject("roAssociativeArray")

      theme.OverhangOffsetSD_X = "72"
    theme.OverhangOffsetSD_Y = "31"
    theme.OverhangSliceSD = "pkg:/images/banner.png"
    'theme.OverhangLogoSD  = "pkg:/images/New_Logo_SD.png"

    theme.OverhangOffsetHD_X = "125"
    theme.OverhangOffsetHD_Y = "35"
    theme.OverhangSliceHD = "pkg:/images/banner.png"
  '  theme.OverhangLogoHD  = "pkg:/images/cartoons_original.png"
    'theme.BackgroundColor = "#FFFFFF"
    
    theme.BackgroundColor = "#D3D3D3"
    app.SetTheme(theme)

End Sub
