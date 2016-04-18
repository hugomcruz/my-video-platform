function getCategoryURL()
  result = false
  deviceID = GetDeviceESN()
  
  baseUrl = "http://app.berzuk.com/app/videos/xml/categories"


  return baseURL
end function


function getFeedURL()
  result = false
  deviceID = GetDeviceESN()
  
  baseUrl = "http://app.berzuk.com/app/videos/xml/feed"


  return baseURL
end function
